<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\SoldItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ItemRevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        $pageData = [
            'title' => 'Report',
            'pageName' => 'Generate Report',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Report</li>',
            'brands' => $brands,
        ];

        return view('pages.report.index')->with($pageData);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start_date = Carbon::parse($validated['start_date'])->startOfDay();
        $end_date = Carbon::parse($validated['end_date'])->endOfDay();

        $items = SoldItem::with('brand')->whereBetween('created_at', [$start_date, $end_date])
            ->get()
            ->groupBy('brand_id')
            ->map(fn($group) => [
                'brand_name' => $group->first()->brand->name,
                'total_revenue' => $group->sum('total_amount'), nb,
            ]);
        return response()->json($items);
    }
}
