<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
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
                return '<span class="badge bg-success">' . $row->role . '</span> ';
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
