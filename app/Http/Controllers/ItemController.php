<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Models;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'All Items',
            'pageName' => 'All Items',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">All Items</li>',

        ];

        return view('pages.item.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $data = [
            "action" => route('item.store'),
            'brands' => $brands,
            "method" => "POST",
        ];
        return view('pages.item.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id', // Ensure brand exists
        ]);

        // Store the data in the database
        $item = new Item;
        $item->name = $request->name;
        $item->amount = $request->amount;
        $item->brand_id = $request->brand_id;
        $item->model_id = $request->model_id;
        $item->save();

        // Redirect to a different page after storing or return with a success message
        return redirect()->route('item.index')->with('success', 'Item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        $data = Item::query();

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
            ->addColumn('amount', function ($row) {
                return $row->amount ?? 0;
            })
            ->orderColumn('amount', function ($query, $order) {
                $query->orderBy('amount', $order)->orderBy('id', $order);
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order)->orderBy('id', $order);
            })
            ->addColumn('brand', function ($row) {
                return $row->brand->name ?? 'N/A';
            })
            ->addColumn('model', function ($row) {
                return $row->models->name ?? 'N/A';
            })
            ->addColumn('actions', function ($row) {
                return '
                        <a href="#" title="Edit Item" data-url="' . route('item.edit', [$row->id]) . '" data-size="md" data-ajax-popup="true"
                      data-title="' . __('Edit Item') . '" data-bs-toggle="tooltip">
                       <i class="fas fa-edit text-info font-18"></i>
                   </a>
                        &nbsp;&nbsp
                        <a href="#" title="Delete" onclick="Delete(' . $row->id . ')" >  <i class="fa fa-trash text-danger font-18"></i></a>
                    ';
            })
            ->rawColumns(['image', 'actions'])
            ->toJson();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the model by ID, if not found return to the model list with an error message
        $item = Item::findOrFail($id);
        // Get all brands to populate the select dropdown for the brand
        $brands = Brand::all();
        $data = [
            'action' => route('item.update', $item->id), // URL for the update action
            'row' => $item,
            'brands' => $brands,
            'method' => 'PUT', // Use PUT method for update
        ];
        return view('pages.item.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the item by ID
        $item = Item::findOrFail($id);

        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id', // Ensure brand exists
        ]);

        // Update the item with the validated data
        $item->name = $request->name;
        $item->amount = $request->amount;
        $item->brand_id = $request->brand_id;
        $item->model_id = $request->model_id;
        $item->save();

        // Redirect with success message
        return redirect()->route('item.index')->with('success', 'Item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        // Redirect with success message
        return redirect()->route('item.index')->with('success', 'Item deleted successfully!');
    }
    public function searchModel(Request $request)
    {
        if (!empty($request->brand_id)) {
            $brandId = $request->input('brand_id');

            // Fetch models associated with the brand
            $models = Models::where('brand_id', $brandId)->get(); // Adjust query based on your database structure
        
            return response()->json(['models' => $models]);
        } else {
            return response()->json(['error' => 'Brand ID is required'], 400);
        }
    }
}
