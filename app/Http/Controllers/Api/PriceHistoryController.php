<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PriceHistory;

class PriceHistoryController extends Controller
{
    public function index($itemId)
    {
        $history = PriceHistory::where('item_id', $itemId)->get();
        return response()->json($history);
    }
}
