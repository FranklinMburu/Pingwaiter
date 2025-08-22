<?php
namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $categories = MenuCategory::with(['items' => function($q) { $q->ordered(); }])->ordered()->get();
        return view('menu.index', compact('categories'));
    }

    // Category CRUD
    public function createCategory()
    {
        return view('menu.category_form');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(MenuCategory::rules());
        MenuCategory::create($data);
        return redirect()->route('menu.index')->with('success', 'Category created.');
    }

    public function editCategory(MenuCategory $category)
    {
        return view('menu.category_form', compact('category'));
    }

    public function updateCategory(Request $request, MenuCategory $category)
    {
        $data = $request->validate(MenuCategory::rules($category->id));
        $category->update($data);
        return redirect()->route('menu.index')->with('success', 'Category updated.');
    }

    public function destroyCategory(MenuCategory $category)
    {
        $category->delete();
        return redirect()->route('menu.index')->with('success', 'Category deleted.');
    }

    // Item CRUD
    public function createItem(MenuCategory $category)
    {
        return view('menu.item_form', compact('category'));
    }

    public function storeItem(Request $request, MenuCategory $category)
    {
        $data = $request->validate(MenuItem::rules());
        $data['category_id'] = $category->id;
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }
        MenuItem::create($data);
        return redirect()->route('menu.index')->with('success', 'Menu item created.');
    }

    public function editItem(MenuCategory $category, MenuItem $item)
    {
        return view('menu.item_form', compact('category', 'item'));
    }

    public function updateItem(Request $request, MenuCategory $category, MenuItem $item)
    {
        $data = $request->validate(MenuItem::rules($item->id));
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }
        $item->update($data);
        return redirect()->route('menu.index')->with('success', 'Menu item updated.');
    }

    public function destroyItem(MenuCategory $category, MenuItem $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('menu.index')->with('success', 'Menu item deleted.');
    }

    // AJAX: Toggle status
    public function toggleCategoryStatus(MenuCategory $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        return response()->json(['success' => true, 'is_active' => $category->is_active]);
    }

    public function toggleItemStatus(MenuCategory $category, MenuItem $item)
    {
        $item->is_available = !$item->is_available;
        $item->save();
        return response()->json(['success' => true, 'is_available' => $item->is_available]);
    }

    // AJAX: Update sorting
    public function updateCategoryOrder(Request $request)
    {
        foreach ($request->input('order', []) as $id => $order) {
            MenuCategory::where('id', $id)->update(['sort_order' => $order]);
        }
        return response()->json(['success' => true]);
    }

    public function updateItemOrder(Request $request, MenuCategory $category)
    {
        foreach ($request->input('order', []) as $id => $order) {
            MenuItem::where('id', $id)->where('category_id', $category->id)->update(['sort_order' => $order]);
        }
        return response()->json(['success' => true]);
    }
}
