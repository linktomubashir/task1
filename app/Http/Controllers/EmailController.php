<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use App\Models\EmailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'Email',
            'pageName' => 'Email',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Email</li>',

        ];

        return view('pages.email.index')->with($pageData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $emails = EmailHistory::all();
        $pageData = [
            'emails' => $emails,
        ];
        return view('pages.email.history')->with($pageData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        $validated = $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments' => 'nullable',

        ]);
        $attachment = $request->file('attachments');
        $emailSent = $this->emailService->sendEmail(
            $request->to,
            $request->subject,
            $request->message,
            $attachment
        );

        if ($emailSent) {
            return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
        }

        return response()->json(['error' => true, 'message' => 'Failed to send the email.']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
