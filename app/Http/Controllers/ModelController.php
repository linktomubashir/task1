<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Models;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        Models::create($validated);

        return redirect()->route('models.index')->with('success', 'Model created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = Models::query();

        if ($request->has('show_trashed') && $request->show_trashed == 'true') {
            $data->onlyTrashed();
        }

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
                return $row->brand->name ?? 'N/A';
            })
            ->filterColumn('brand_name', function ($query, $keyword) {
                $query->whereHas('brand', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->addColumn('items', function ($row) {
                return $row->items->count() ;
            })
            ->orderColumn('items', function ($query, $order) {
                $query->withCount('items')->orderBy('items_count', $order);
            })
            ->addColumn('actions', function ($row) {
                if ($row->trashed()) {
                    return '
                    <a href="#" title="Restore" onclick="handleAction(' . $row->id . ',\'restore\')" data-bs-toggle="tooltip">
                        <i class="fas fa-redo-alt"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Permanent Delete" onclick="handleAction(' . $row->id . ', \'permanentDelete\')" data-bs-toggle="tooltip">
                        <i class="fa fa-trash text-danger font-18"></i>
                    </a>';
                } else {
                    return '
                    <a href="#" title="Edit Model" data-url="' . route('models.edit', [$row->id]) . '" data-size="small" data-ajax-popup="true"
                       data-title="' . __('Edit Model') . '" data-bs-toggle="tooltip">
                        <i class="fas fa-edit text-info font-18"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Delete" onclick="handleAction(' . $row->id . ', \'delete\')" data-bs-toggle="tooltip">
                        <i class="fa fa-trash text-danger font-18"></i>
                    </a>';
                }
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
            'action' => route('models.update', $model->id), // URL for the update action
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $model = Models::findOrFail($id);

        $model->update($validated);
        return redirect()->route('models.index')->with('success', 'Model updated successfully.');
    }
    public function destroy(string $id)
    {
        $model = Models::findOrFail($id);
        $model->delete();
        return redirect()->route('models.index')->with('success', 'Model deleted successfully.');
    }
    public function restore($id)
    {
        $model = Models::withTrashed()->find($id);
        if ($model) {
            $model->restore();
            return response()->json(['success' => 'Model restored successfully']);
        }

        return response()->json(['error' => 'Model not found'], 404);
    }

    public function forceDelete($id)
    {
        $model = Models::withTrashed()->find($id);
        if ($model) {
            $model->forceDelete();
            return response()->json(['message' => 'Model permanently deleted']);
        }

        return response()->json(['message' => 'Model not found'], 404);
    }

}
