<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'Brands',
            'pageName' => 'Brands',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Brands</li>',
            
        ];

        return view('pages.brand.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            "action" => route('brands.store'),
            "method" => "POST",
        ];
        return view('pages.brand.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    Brand::create([
        'name' => $request->name,
    ]);

    return redirect()->route('brands.index')->with('success', 'Brand created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = Brand::query();
    
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
        ->addColumn('items', function ($row) {
            return $row->items->count();  // Counting the related items
        })
        // Sorting logic for 'items' column
        ->orderColumn('items', function ($query, $order) {
            $query->withCount('items')->orderBy('items_count', $order);
        })
        // Adding 'models' column (with count)
        ->addColumn('models', function ($row) {
            return $row->models->count();  // Counting the related models
        })
        
        // Sorting logic for 'models' column
        ->orderColumn('models', function ($query, $order) {
            $query->withCount('models')->orderBy('models_count', $order);
        })
        ->addColumn('actions', function ($row) {
            return '
                <a href="#" title="Edit Models" data-url="' . route('brands.edit', [$row->id]) . '" data-size="small" data-ajax-popup="true"
                    data-title="' . __('Edit Model') . '" data-bs-toggle="tooltip">
                    <i class="fas fa-edit text-info font-18"></i>
                </a>
                &nbsp;&nbsp;
                <a href="#" title="Delete" onclick="Delete(' . $row->id . ')">
                    <i class="fa fa-trash text-danger font-18"></i>
                </a>
            ';
        })
        ->rawColumns(['actions'])  //  'actions' column is raw for HTML content
        ->toJson();
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        $data = [
            "action" => route('brands.update',[$brand->id]),
            'row' => $brand,
            "method" => "PUT",
        ];
        return view('pages.brand.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
         $request->validate([
            'name' => 'required|string|max:255', 
        ]);
        $brand = Brand::findOrFail($id);

        $brand->update([
            'name' => $request->name,
        ]);
        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json(['success' => true]);
    }
}
