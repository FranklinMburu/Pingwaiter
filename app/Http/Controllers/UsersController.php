<?php

namespace App\Http\Controllers;

use App\Models\MealType;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function dashboard()
    {
        foreach (auth()->user()->role->values as $role) {
            if ($role->name == 'admin') {
                $users = User::get();
                $alluser = $users->count();
                $adminuser = 0;
                $tickeruser = 0;
                foreach ($users as $user) {
                    foreach ($user->roles as $urole) {
                        if ($urole->name == 'admin') {
                            $adminuser += 1;
                        } elseif ($urole->name == 'ticker') {
                            $tickeruser += 1;
                        }
                    }
                }
                $allstudent = Student::all()->count();
                $activestudent = Student::where('status', 'Active')->count();
                $inactivestudent = Student::where('status', 'Inactive')->count();
                $cafestudent = Student::where('cafe_status', '0')->count();
                $nonecafestudent = Student::where('cafe_status', '1')->count();
                $regular = Student::where('type', '0')->count();
                $muslim = Student::where('type', '1')->count();
                $christian = Student::where('type', '2')->count();

                return view('dashboard', compact('alluser', 'adminuser', 'tickeruser', 'allstudent', 'activestudent', 'inactivestudent', 'cafestudent', 'nonecafestudent', 'regular', 'muslim', 'christian'));
            } else {
                $mealtypes = MealType::all();

                return view('dashboard', compact('mealtypes'));
            }
        }
    }

    public function resetpassword($id)
    {
        $user = User::find($id);
        if ($user) {
            $password = Str::random(8);
            $user->password = $password;
            $reset = $user->save();
            if ($reset) {
                return response()->json(['success' => 'New password=> '.$password]);
            } else {
                return response()->json(['errors' => 'An Error Occured please try again']);
            }
        } else {
            return response()->json(['errors' => 'An Error Occured please try again']);
        }
    }

    public function getusers()
    {
        $user = User::orderBy('created_at', 'desc');

        return datatables()
            ->of($user)
            ->addColumn('role', function ($row) {
                return $row->roles->pluck('name')->toArray();
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        // $userRole = $users->roles->pluck('name')->toArray();
        $roles = Role::latest()->get();

        return view('users.index1', compact('users', 'roles'));
    }

    public function show($id)
    {
        $users = User::find($id);
        $cid = $users->created_by;
        $crdate = Carbon::createFromFormat('Y-m-d H:i:s', $users->created_at)
            ->timezone('GMT+3')
            ->format('Y-m-d @ g:i:s A');
        $upgdate = Carbon::createFromFormat('Y-m-d H:i:s', $users->updated_at)
            ->timezone('GMT+3')
            ->format('Y-m-d @ g:i:s A');
        $cr = User::find($cid);
        if (empty($cr)) {
            $cr = '';
        }
        $uid = $users->updated_by;
        if (empty($uid)) {
            $ur = '';
        } else {
            $ur = User::find($uid);
        }

        return response()->json([
            'status' => 200,
            'users' => $users,
            'cr' => $cr,
            'ur' => $ur,
            'crdate' => $crdate,
            'upgdate' => $upgdate,
            'userRole' => $users->roles->pluck('name')->toArray(),
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();

            return response()->json([
                'success' => 'User Deleted Successfully',
            ]);
        }
    }

    public function store(Request $request)
    {
        $id = $request->edit_id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:250',
            'email' => ['required', 'email', 'min:2', 'max:255', Rule::unique('users')->ignore($id)],
            'username' => ['required', 'string', 'min:2', 'max:255', Rule::unique('users')->ignore($id)],
            'status' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()]);
        } else {
            if ($id) {
                $record = User::findOrFail($id);
                $record->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'status' => $request->status,
                    'updated_by' => auth()->user()->id,
                ]);
                $record->syncRoles($request->get('role'));
            } else {
                $record = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'status' => $request->status,
                    'password' => 'password',
                    'created_by' => auth()->user()->id,
                ]);

                $record->syncRoles($request->get('role'));
            }
            /*
            User::updateOrCreate([
                ['id' => $id],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => 'password',
                ],
            ]);*/
            if ($id) {
                return response()->json(['success' => 'User Successfully Updated']);
            } else {
                return response()->json(['success' => 'User Successfully Created']);
            }
        }
    }

    public function createOrUpdate(Request $request, $id = null)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'string', 'min:2', 'max:255', Rule::unique('users')],
            'username' => ['required', 'string', 'min:2', 'max:255', Rule::unique('users')],
        ])->validate();

        $record = User::updateOrCreate([
            ['id' => $id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => 'password',
            ],
        ]);
        $record->syncRoles($request->get('role'));
        $message = 'User Created Successfully.';

        $resp['status'] = 'success';
        $resp['msg'] = $message;
        if ($resp['status'] == 'success' && isset($resp['msg'])) {
            return json_encode($resp);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);

        return response()->json([
            'success' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get(),
        ]);
        /* return view('users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get(),
        ]);*/
    }

    public function update(Request $request, string $id)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
        ])->validate();

        $record = User::findOrFail($id);
        $record->updateOrCreate([
            ['id' => $id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
            ],
        ]);

        $record->syncRoles($request->get('role'));

        return redirect()->route('users')->withSuccess(__('User updated successfully.'));
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('login');
    }

    public function assignblock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'block' => 'required',
        ]);
        if ($validator->passes()) {
            $user = User::find($request->usr_id);
            $update = $user->update([
                'block' => $request->block,
            ]);
            if ($update) {
                return response()->json(['success' => 'Block assigned Successfully.']);
            } else {
                return response()->json(['errors' => 'An Error Occured!']);
            }
        } elseif ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()]);
        }
    }
}
