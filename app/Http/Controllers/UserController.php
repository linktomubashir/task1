<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'All Users',
            'pageName' => 'All Users',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">All Users</li>',
        ];

        return view('pages.user.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        // dd($roles);
        $data = [
            "action" => route('users.store'),
            "method" => "POST",
            'roles' => $roles,
        ];
        return view('pages.user.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated =  $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'role'=> ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]) + [
            'password' => Hash::make($request->password),
        ];

        $user = User::create($validated);

        $user->assignRole($request->role);
        return redirect()->back()->with('success', 'User ' . $user->name . ' created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = User::query();

        return DataTables::of($data)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order)->orderBy('id', $order);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
           
            ->addColumn('role', function ($row) {
                // return $row->getRoleNames()->implode(', ');
                return '<span class="badge bg-success">' . $row->getRoleNames()->implode(', ') . '</span> ';
            })
            ->addColumn('actions', function ($row) {
                return '
                  <a href="#" title="Edit Permission" data-url="' . route('users.edit', [$row->id]) . '" data-size="lg" data-ajax-popup="true"
                       data-title="' . __('Edit Permission') . '" data-bs-toggle="tooltip"> <i class="fas fa-edit"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Delete" onclick="handleAction(' . $row->id . ', \'delete\')" data-bs-toggle="tooltip">
                        <i class="fa fa-trash text-danger font-18"></i>
                    </a>';
            })
            ->rawColumns(['role','actions'])
            ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $row = User::findOrFail($id);
        $roles = Role::all();

        $data = [
            'action' => route('users.update', $row->id),
            'method' => 'PUT',
            'row' => $row,
            'roles' => $roles,
        ];
        return view('pages.user.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $validated =  $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required'],
        ]);
        $user = User::findOrFail($id);
        $user->update($validated);
        $user->syncRoles([]);  // Removes role
        // dd($user);
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User ' . $user->name . ' updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('roles.index')->with('success', 'Role ' . $user->name . ' deleted successfully!');
    }
}
