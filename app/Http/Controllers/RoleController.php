<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'Role',
            'pageName' => 'Role',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Role</li>',
        ];

        return view('pages.role.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        $data = [
            "action" => route('roles.store'),
            "method" => "POST",
            "permissions" => $permissions,
        ];
        return view('pages.role.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:40|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        $permissions = $request->permissions;
        if ($permissions) {
            foreach ($permissions as $permission) {
                $p = Permission::findOrFail($permission);
                $role->givePermissionTo($p);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Role ' . $role->name . ' created successfully!');
    }

    /**
     * Display the specified resource.
     */
   
    public function show(Request $request)
    {
        $data = Role::query();

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
           
            ->addColumn('permissions', function ($row) {
                $permissions = '';
                foreach ($row->permissions as $permission) {
                    $permissions .= '<span class="badge bg-success">' . $permission->name . '</span> ';
                }
            
                return $permissions;
            })
            ->addColumn('actions', function ($row) {
                return '
                  <a href="#" title="Edit Permission" data-url="' . route('roles.edit', [$row->id]) . '" data-size="lg" data-ajax-popup="true"
                       data-title="' . __('Edit Permission') . '" data-bs-toggle="tooltip"> <i class="fas fa-edit"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Delete" onclick="handleAction(' . $row->id . ', \'delete\')" data-bs-toggle="tooltip">
                        <i class="fa fa-trash text-danger font-18"></i>
                    </a>';
            })
            ->rawColumns(['permissions','actions'])
            ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the role and permissions for the form
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();

        $data = [
            'action' => route('roles.update', $role->id),
            'method' => 'PUT',
            'role' => $role,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions,
        ];
        return view('pages.role.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|max:40|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $permissions = $request->permissions;

        $role->update([
            'name' => $request->name,
        ]);
        $allPermissions = Permission::all();

        foreach($allPermissions as $permission) {
            $role->revokePermissionTo($permission);
        }
       
        if ($permissions) {
            foreach ($permissions as $permission) {
                $p = Permission::findOrFail($permission);
                $role->givePermissionTo($p);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Role ' . $role->name . ' updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role ' . $role->name . ' deleted successfully!');
    }
}
