<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Models;
use Yajra\DataTables\DataTables;
use App\Models\Brand;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'All Models',
            'pageName' => 'All Models',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">All Models</li>',
            
        ];

        return view('pages.models.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $data = [
            "action" => route('models.store'),
            'brands' => $brands,
            "method" => "POST",
        ];
        return view('pages.models.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',  
        'brand_id' => 'required|exists:brands,id',
    ]);

    Models::create([
        'name' => $request->input('name'),
        'brand_id' => $request->input('brand_id'),
    ]);

    return redirect()->route('models.index')->with('success', 'Model created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $data = Models::query();

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
           ->addColumn('brand_name', function ($row) {
               return $row->brand->name;  
           })
           ->filterColumn('brand_name', function ($query, $keyword) {
            $query->whereHas('brand', function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        })
           ->addColumn('items', function ($row) {
               return $row->items->count();  
           })
           ->orderColumn('items', function ($query, $order) {
            $query->withCount('items')->orderBy('items_count', $order);  
        })
           ->addColumn('actions', function ($row) {
               return '
                   <a href="#" title="Edit Model" data-url="' . route('models.edit', [$row->id]) . '" data-size="small" data-ajax-popup="true"
                      data-title="' . __('Edit Model') . '" data-bs-toggle="tooltip">
                       <i class="fas fa-edit text-info font-18"></i>
                   </a>
                   &nbsp;&nbsp;
                   <a href="#" title="Delete" onclick="Delete(' . $row->id . ')">
                       <i class="fa fa-trash text-danger font-18"></i>
                   </a>
               ';
           })
           ->rawColumns(['actions'])
           ->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = Models::findOrFail($id);
        $brands = Brand::all();
        $data = [
            'action' => route('models.update', $model->id),  // URL for the update action
            'row' => $model,
            'brands' => $brands,
            'method' => 'PUT',  
        ];
        return view('pages.models.form')->with($data);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',  
            'brand_id' => 'required|exists:brands,id', 
        ]);
    
        $model = Models::findOrFail($id);
        
        $model->update([
            'name' => $request->input('name'),
            'brand_id' => $request->input('brand_id'),
        ]);
        return redirect()->route('models.index')->with('success', 'Model updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Models::findOrFail($id);
        $model->delete();
        return redirect()->route('models.index')->with('success', 'Model deleted successfully.');
    }
}
