<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\TableAccessToken;
use App\Models\TablePing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    public function index()
    {
        return view('table.index');
    }

    public function gettables()
    {
        $tables = Table::select('tables.*')
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->leftJoin('waiter_table_assignments', function ($join) {
                $join->on('tables.id', '=', 'waiter_table_assignments.table_id')
                    ->where('waiter_table_assignments.user_id', auth()->id());
            })
            ->selectRaw('CASE WHEN waiter_table_assignments.id IS NOT NULL THEN 1 ELSE 0 END as is_assigned')
            ->orderBy('created_at', 'desc');

        return datatables()->of($tables)
            ->addColumn('picture', function ($row) {
                $html = '';
                if ($row->picture) {
                    $url = asset('/uploads/table/pictures/'.$row->picture);

                    return '<a href="'.$url.'" data-fancybox="table-gallery" data-caption="Table '.$row->table_code.'">
                            <img src="'.$url.'" alt="Table Image" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                           </a>';
                }

                return $html;
            })
            ->addColumn('assignment', function ($row) {
                if (auth()->user()->isWaiter() && auth()->user()->isRestaurant()) {
                    return '';
                }

                return '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input table-assignment"
                        id="tableAssignment_'.$row->id.'"
                        data-table-id="'.$row->id.'"
                        '.($row->is_assigned ? 'checked' : '').'>
                    <label class="custom-control-label" for="tableAssignment_'.$row->id.'"></label>
                </div>';
            })
            ->rawColumns(['picture', 'assignment'])
            ->make(true);
    }

    public function storeOrUpdate(Request $request, $id)
    {
        $rules = [
            'table_code' => 'required|string|max:100|unique:tables,table_code,'.($id != 'add' ? $id : 'NULL'),
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $table = $id == 'add' ? new Table : Table::findOrFail($id);

        $table->table_code = $request->table_code;
        $table->size = $request->size;
        $table->location = $request->location;
        $table->description = $request->description;
        $table->restaurant_id = auth()->user()->restaurant->id;

        if ($id !== 'add') {
            // Handle image removal
            if ($request->remove_picture == '1') {
                // Delete the existing image file
                if ($table->picture) {
                    $path = public_path('/uploads/table/pictures/'.$table->picture);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                $table->picture = null;
            }
        }

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/table/pictures');

            // Check if the directory exists, if not create it
            if (! File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $image->move($destinationPath, $name);
            $table->picture = $name;
        }

        $table->save();

        return response()->json(['success' => 'Table Setup '.($id == 'add' ? 'created' : 'updated').' successfully']);
    }

    public function edit($id)
    {
        $table = Table::find($id);
        if ($table) {
            return response()->json(['table' => $table]);
        }
    }

    public function delete($id)
    {
        $table = Table::find($id);
        $delete = $table->delete();
        if ($delete) {
            return response()->json(['success' => 'Table Setup deleted successfully!']);
        }
    }

    public function printQr($id)
    {
        $table = Table::with('restaurant')->findOrFail($id);

        // Generate URL with token
        $tableUrl = URL::temporarySignedRoute(
            'table.menu',
            now()->addMinutes(30),
            ['table' => $table->table_code]
        );

        $qrCode = QrCode::size(300)->generate($tableUrl);

        return view('table.qr-print', compact('table', 'qrCode', 'tableUrl'));
    }

    public function handleQrScan($id)
    {
        $table = Table::with('restaurant')->findOrFail($id);

        // Generate new access token
        $token = \Str::random(32);
        $expiresAt = now()->addMinutes(5);

        // Delete any existing tokens for this table
        TableAccessToken::where('table_id', $id)->delete();

        // Create new token
        TableAccessToken::create([
            'table_id' => $id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        // Redirect to the details page with token
        return redirect()->route('tables.details', [
            'id' => $id,
            'token' => $token,
        ]);
    }

    public function showTableDetails($id, $token = null)
    {
        $table = Table::with('restaurant')->findOrFail($id);

        // If no token provided, generate new one and redirect
        if (! $token) {
            // Generate new access token
            $token = \Str::random(32);
            $expiresAt = now()->addMinutes(5);

            // Delete any existing tokens for this table
            TableAccessToken::where('table_id', $id)->delete();

            // Create new token
            TableAccessToken::create([
                'table_id' => $id,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // Redirect to the same route with token
            return redirect()->route('tables.details', [
                'id' => $id,
                'token' => $token,
            ]);
        }

        // Token provided - validate it
        $accessToken = TableAccessToken::where('table_id', $id)
            ->where('token', $token)
            ->first();

        if (! $accessToken || $accessToken->isExpired()) {
            return redirect()->route('expired.link');
        }

        return view('table.details', compact('table'));
    }

    public function viewTableDetails(Request $request, $id)
    {
        $token = $request->token;
        $table = Table::with('restaurant')->findOrFail($id);
        // If no token provided, generate new one and redirect
        if (! $token) {
            // Generate new access token
            $token = \Str::random(32);
            $expiresAt = now()->addMinutes(10);

            // Create new token
            TableAccessToken::create([
                'table_id' => $id,
                'token' => $token,
                'expires_at' => $expiresAt,
                'first_scan' => true,
            ]);

            return redirect()->route('tables.details', [
                'id' => $id,
                'token' => $token,
            ]);
        }

        // Token provided - validate it
        $accessToken = TableAccessToken::where('table_id', $id)
            ->where('token', $token)
            ->first();

        if (! $accessToken || $accessToken->isExpired()) {
            return redirect()->route('expired.link');
        }

        return view('table.details', compact('table', 'accessToken'));
    }

    public function callWaiter(Request $request, $id)
    {
        $table = Table::find($id);

        $ping = TablePing::create([
            'table_id' => $table->id,
            'restaurant_id' => $table->restaurant_id,
            'ip_address' => $request->ip(),
            'is_attended' => false, // Ensure this is set to false initially
        ]);

        return response()->json(['success' => true, 'ping_id' => $ping->id]);
    }

    public function endcalling($pingId)
    {
        $ping = TablePing::find($pingId);

        if ($ping) {
            $ping->update(['seen' => true, 'attended_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    public function waitTable($id)
    {
        $table = Table::with('restaurant')->findOrFail($id);

        return view('table.waittable', compact('table'));
    }

    public function toggleAssignment(Request $request, $tableId)
    {
        $user = auth()->user();
        $assigned = $request->assigned === 'true';

        if ($assigned) {
            // Assign table to waiter
            \DB::table('waiter_table_assignments')->insert([
                'user_id' => $user->id,
                'table_id' => $tableId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Remove assignment
            \DB::table('waiter_table_assignments')
                ->where('user_id', $user->id)
                ->where('table_id', $tableId)
                ->delete();
        }

        return response()->json(['success' => true]);
    }
}
