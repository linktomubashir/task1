<?php

namespace App\Http\Controllers;

use App\Models\SmsHistory;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'SMS',
            'pageName' => 'SMS',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">SMS</li>',

        ];

        return view('pages.sms.index')->with($pageData);
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
       
        $validated = $request->validate([
            'to' => 'required',  
            'message' => 'required|string|max:160', 
        ]);

        $to = $validated['to'];
        $message = $validated['message'];

        $response = $this->smsService->sendSms($to, $message);

        return response()->json(['status' => 'success','message_sid' => $response]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {      
            $smsHistory = SmsHistory::orderBy('created_at', 'desc')->get();
            return view('sms_history', compact('smsHistory'));
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
