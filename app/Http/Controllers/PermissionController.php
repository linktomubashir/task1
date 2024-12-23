<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Permission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'Permission',
            'pageName' => 'Permission',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Permission</li>',
        ];

        return view('pages.permissions.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $data = [
            "action" => route('permissions.store'),
            "method" => "POST",
            "roles" => $roles,
        ];
        return view('pages.permissions.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|max:40',
        ]);

        $permission = Permission::firstOrCreate([
            'name' => $request->name,
        ]);

        $roles = $request->roles;

        if (!empty($roles)) {
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail();
                $r->givePermissionTo($permission);
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Permission ' . $permission->name . ' added!');
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request)
    {
        $data = Permission::query();
// dd($data);
        return DataTables::of($data)
            ->addColumn('id', function ($row) {
                return $row->id;
        //   dd($row->name);
                
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
            ->addColumn('roles', function ($row) {
                $roles = '';
                // dd($row->roles);
                foreach ($row->roles as $role) {
                    $roles .= '<span class="badge bg-success">' . $role->name . '</span> ';
                }
            
                return $roles;
            })
            ->addColumn('actions', function ($row) {
                {
                    return '
                    <a href="#" title="Edit Permission" data-url="' . route('permissions.edit', [$row->id]) . '" data-size="lg" data-ajax-popup="true"
                       data-title="' . __('Edit Permission') . '" data-bs-toggle="tooltip">
                        <i class="fas fa-edit text-info font-18"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Delete" onclick="handleAction(' . $row->id . ', \'delete\')" data-bs-toggle="tooltip">
                        <i class="fa fa-trash text-danger font-18"></i>
                    </a>';
                }
            })
            ->rawColumns(['roles','actions'])
            ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $permission = Permission::findOrFail($id);
        // dd($permission->roles);
        $assignedRoles = $permission->roles->pluck('id')->toArray();

        $data = [
            "action" => route('permissions.update', [$id]),
            "method" => "PUT",
            "roles" => $roles,
            "permission" => $permission,
            "assignedRoles" => $assignedRoles,
        ];
        return view('pages.permissions.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|max:40',
        ]);
        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name,
        ]);

        $roles = $request->roles;

        if (!empty($roles)) {
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail();
                $r->givePermissionTo($permission);
            }
        }

        return redirect()->route('permissions.index')->with('success', 'Permission ' . $permission->name . ' added!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
            $permission->delete(); 

        return redirect()->route('permissions.index')->with(
            'success', 'Permission ' . $permission->name . ' deleted!'
        );
    }
}
