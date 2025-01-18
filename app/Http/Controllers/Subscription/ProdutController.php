<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Price;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProdutController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'Subscription Products',
            'pageName' => 'Subscription Products',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Subscription Products</li>',

        ];

        return view('pages.subscription.product.index')->with($pageData);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            "action" => route('product.store'),
            "method" => "POST",
        ];
        return view('pages.subscription.product.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|string|in:day,week,month,year',
            'currency' => 'required|string'
        ]);

        [$stripeProduct, $stripePrice] = $this->stripeService->create($validated);

        // Save Data In Data Base
        $product = Product::create([
            'stripe_id' => $stripeProduct->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $price = Price::create([
            'product_id' => $product->id,
            'stripe_id' => $stripePrice->id,
            'unit_amount' => $request->price,
            'currency' => $request->currency,
            'interval' => $request->interval,
        ]);
        return redirect()->back()->with('success', 'Product and Price created successfully in Stripe!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = Product::query();

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
            ->addColumn('description', function ($row) {
                return $row->description; 
            })
            ->addColumn('price', function ($row) {
                $price = $row->trashed() ? $row->price()->onlyTrashed()->first() : $row->price()->first();
                return $price ? $price->unit_amount : 'N/A'; 
            })
            ->addColumn('currency', function ($row) {
                $price = $row->trashed() ? $row->price()->onlyTrashed()->first() : $row->price()->first();
                return $price ? $price->currency : 'N/A'; 
            })
            ->addColumn('actions', function ($row) {
                if ($row->trashed()) {
                    return '
                    <a href="#" title="Restore" onclick="handleAction(' . $row->id . ', \'restore\')" data-bs-toggle="tooltip">
                        <i class="fas fa-redo-alt"></i>
                    </a>';

                }else{
                    return '
                    <a href="#" title="Edit Models" data-url="' . route('product.edit', [$row->id]) . '" data-size="lg" data-ajax-popup="true"
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
        $product = Product::findOrFail($id);
        $price = $product->price()->first();
        $data = [
            "action" => route('product.update', [$product->id]),
            'product' => $product,
            "price" => $price,
            "method" => "PUT",
        ];
        return view('pages.subscription.product.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|string|in:day,week,month,year',
            'currency' => 'required|string'
        ]);
    
        $product = Product::where('id', $id)->first();
        $price = $product->price()->first();
        if (!$product || !$price) {
            return redirect()->back()->with('error', 'Data not found!');
        }

        [$stripeProduct, $stripePrice] = $this->stripeService->update($validated, $product->stripe_id, $price->stripe_id);
       
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        $price->update([
            'unit_amount' => $request->price,
            'currency' => $request->currency,
            'interval' => $request->interval,
        ]);
    
        return redirect()->route('products.index')->with('success', 'Product and Price updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('id', $id)->first();
        $price = $product ? $product->price()->first() : null;

        if (!$product || !$price) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found!'
            ], 404);
        }
    
        $this->stripeService->archive($product->stripe_id,$price->stripe_id);

    
        $price->delete();
        $product->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Product and Price deleted successfully!'
        ], 200);
    }   
    public function restore(string $id)
    {
        $product = Product::where('id', $id)->withTrashed()->first();
        $price = $product ? $product->price()->withTrashed()->first() : null;
    
        if (!$product || !$price || !$product->trashed() || !$price->trashed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product or Price not found, or not deleted!'
            ], 404);
        }
    
        $this->stripeService->restore($product->stripe_id,$price->stripe_id);

        $product->restore();
        $price->restore();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Product and Price restored successfully!'
        ]);
    }        
}


