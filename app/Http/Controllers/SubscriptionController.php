<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $price   = '';
        $product = '';
        if ($user->currentSubscription()) {
            $data         = $user->currentSubscription();
            $subscription = $data['subscription'];
            $product      = $data['product'];
            $price        = $data['price'];
        }
        $pageData = [
            'data'    => $data,
            'price'   => $price,
            'product' => $product,

        ];

        return view('pages.subscription.edit')->with($pageData);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function show()
    {
        $products = Product::all();
        return redirect()->back()->with('products', $products)->with('showSubscriptionModal', true);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'plan_id'        => 'required|string',
        ]);

        $user = auth()->user();

        try {
            $subscription = $this->subscriptionService->createOrUpdateSubscription($user, $validated);

            return redirect()->route('dashboard')->with('success', 'You have successfully subscribed!');

        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'There was an issue with your subscription. Please try again.');
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function cancel(Request $request)
    {
        $user = User::find('21');

        $user->subscription('default')->cancel();

        return back()->with('success', 'Subscription canceled.');
    }
    /**
     * Display the specified resource.
     */
    public function resume(Request $request)
    {
        $user   = auth()->user();
        $action = $request->action;

        try {
            $subscription = $this->subscriptionService->renewSubscription($user, $action);

            return response()->json(['status' => 'success', 'message' => 'Subscription resumed successfully!'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong while resuming your subscription. Please try again later.'], 500);
        }
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
