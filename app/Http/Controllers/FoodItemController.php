<?php

namespace App\Http\Controllers;

use App\Exports\FoodMenuExport;
use App\Imports\FoodMenuImport;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\FoodStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FoodItemController extends Controller
{
    public function index()
    {
        $styles = FoodStyle::where('restaurant_id', auth()->user()->restaurant->id)
            ->pluck('name', 'id');
        $categories = FoodCategory::pluck('name', 'id');

        return view('food-menu.index', compact('styles', 'categories'));
    }

    public function getfooditems()
    {
        $items = FoodItem::with('styles')
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->orderBy('created_at', 'desc');

        return datatables()->of($items)
            ->addColumn('picture', function ($row) {
                $html = '';
                if ($row->picture) {
                    $pictures = is_array($row->picture) ? $row->picture : [$row->picture];

                    // Create a gallery container with unique ID for each food item
                    $galleryId = 'food-gallery-'.$row->id;
                    $html .= '<div class="food-gallery">';

                    if (count($pictures) > 0) {
                        // Display the first image as thumbnail with link to fancybox gallery
                        $url = asset('/uploads/food/pictures/'.$pictures[0]);
                        $html .= '<a href="'.$url.'" data-fancybox="'.$galleryId.'">';
                        $html .= '<img src="'.$url.'" alt="Food Image" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">';
                        $html .= '</a>';

                        // If there are more images, add a badge and hidden links for other images
                        if (count($pictures) > 1) {
                            $html .= '<span class="badge badge-info ml-1">+'.(count($pictures) - 1).'</span>';

                            // Add hidden links for other images in the gallery
                            for ($i = 1; $i < count($pictures); $i++) {
                                $url = asset('/uploads/food/pictures/'.$pictures[$i]);
                                $html .= '<a href="'.$url.'" data-fancybox="'.$galleryId.'" style="display: none;"></a>';
                            }
                        }
                    }

                    $html .= '</div>';
                }

                return $html;
            })
            ->addColumn('video', function ($row) {
                return $row->video;
            })
            ->addColumn('style', function ($row) {
                return $row->styles->pluck('name')->implode(', ');
            })
            ->addColumn('category', function ($row) {
                return $row->category->name;
            })
            ->rawColumns(['picture'])
            ->addIndexColumn()
            ->make(true);
    }

    public function storeOrUpdate(Request $request, $id)
    {
        $rules = [
            'item_code' => 'required|string|max:100|unique:food_items,item_code,'.($id != 'add' ? $id : 'NULL').',id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:food_categories,id',
            'picture.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $foodItem = $id == 'add' ? new FoodItem : FoodItem::findOrFail($id);
        $foodItem->item_code = $request->item_code;
        $foodItem->name = $request->name;
        $foodItem->price = $request->price;
        $foodItem->category_id = $request->category_id;
        $foodItem->description = $request->description;
        $foodItem->restaurant_id = auth()->user()->restaurant->id;

        // Handle multiple pictures
        if ($request->hasFile('picture')) {
            $picture = [];

            // If editing, get existing pictures
            if ($id != 'add' && $foodItem->picture) {
                $picture = is_array($foodItem->picture) ? $foodItem->picture : [$foodItem->picture];
            }

            foreach ($request->file('picture') as $image) {
                $name = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/food/pictures');

                // Check if the directory exists, if not create it
                if (! File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true, true);
                }

                $image->move($destinationPath, $name);
                $picture[] = $name;
            }

            $foodItem->picture = $picture;
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time().'.'.$video->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/food/videos');

            // Check if the directory exists, if not create it
            if (! File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $video->move($destinationPath, $videoName);
            $foodItem->video = $videoName;
        }

        $foodItem->save();

        // Handle food styles
        if ($request->has('style')) {
            $foodItem->styles()->sync($request->style);
        } else {
            $foodItem->styles()->detach();
        }

        return response()->json(['success' => 'Food item '.($id == 'add' ? 'created' : 'updated').' successfully']);
    }

    public function edit($id)
    {
        $item = FoodItem::with('styles')->find($id);
        if ($item) {
            $item->style_ids = $item->styles->pluck('id')->toArray();

            return response()->json(['item' => $item]);
        }
    }

    public function delete($id)
    {
        $item = FoodItem::find($id);

        // Check if there are any related orders
        if ($item->orders()->exists()) {
            return response()->json(['error' => 'Cannot delete this food item because it has related orders. Consider disabling it instead.'], 422);
        }

        $delete = $item->delete();
        if ($delete) {
            return response()->json(['success' => 'Food Menu deleted successfully!']);
        }
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            // Validate the file
            $request->validate([
                'file' => 'mimes:csv,xlsx,xls|max:2048',
            ]);

            try {
                Excel::import(new FoodMenuImport(auth()->user()->restaurant->id), $request->file('file'));

                return back()->with('success', 'Data has been imported successfully.');
            } catch (\Throwable $e) {
                return back()->with('error', 'Import failed: '.$e->getMessage());
            }
        }

        return back()->with('error', 'Please upload a valid file.');
    }

    public function export()
    {
        return Excel::download(new FoodMenuExport, 'food_menu.xlsx');
    }

    /**
     * Delete a specific media file (picture or video) from a food item
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMedia(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:picture,video',
            'filename' => 'required|string',
        ]);

        $item = FoodItem::findOrFail($id);
        $type = $request->type;
        $filename = $request->filename;

        if ($type === 'picture') {
            // Handle picture deletion
            if ($item->picture && is_array($item->picture)) {
                // Remove the filename from the pictures array
                $pictures = array_filter($item->picture, function ($pic) use ($filename) {
                    return $pic !== $filename;
                });

                // Update the item with the new pictures array
                $item->picture = array_values($pictures); // Reset array keys
                $item->save();

                // Delete the file from storage
                $path = public_path('/uploads/food/pictures/'.$filename);
                if (file_exists($path)) {
                    unlink($path);
                }

                return response()->json(['success' => 'Picture deleted successfully']);
            }
        } elseif ($type === 'video') {
            // Handle video deletion
            if ($item->video === $filename) {
                // Delete the file from storage
                $path = public_path('/uploads/food/videos/'.$filename);
                if (file_exists($path)) {
                    unlink($path);
                }

                // Update the item to remove the video reference
                $item->video = null;
                $item->save();

                return response()->json(['success' => 'Video deleted successfully']);
            }
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
