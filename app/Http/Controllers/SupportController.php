<?php

namespace App\Http\Controllers;

use App\Events\ReplyEmailEvent;
use App\Models\SupportRequest;
use Illuminate\Http\Request;
use yajra\DataTables\DataTables;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageData = [
            'title' => 'Service',
            'pageName' => 'Service',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">Service</li>',
        ];

        return view('pages.support_message.index')->with($pageData);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = SupportRequest::query();

        return DataTables::of($data)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('name', function ($row) {
                return $row->name ?? 'N/A';
            })
            ->orderColumn('name', function ($query, $order) {
                $query->join('users', 'support_requests.user_id', '=', 'users.id')
                    ->orderBy('users.name', $order);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->addColumn('email', function ($row) {
                return $row->email ?? 'N/A';
            })
            ->addColumn('subject', function ($row) {
                return $row->subject;
            })
            ->addColumn('message', function ($row) {
                return $row->message;
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pending' => '<span class="badge bg-warning">Pending</span>',
                    'answered' => '<span class="badge bg-success">Answered</span>',
                    default => '<span class="badge bg-secondary">Unknown</span>',
                };
            })
            ->addColumn('reply_message', function ($row) {
                return $row->reply_message ?? '<em>No reply yet</em>';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('actions', function ($row) {
                return '
                <a href="#" title="Reply" data-url="' . route('messages.edit', [$row->id]) . '" data-size="small" data-ajax-popup="true"
                   data-title="' . __('Reply to Model') . '" data-bs-toggle="tooltip">
                    <i class="fas fa-reply text-success font-18"></i>
                </a>';
            })
            ->rawColumns(['status', 'reply_message', 'actions'])

            ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $support = SupportRequest::findOrFail($id);

        $data = [
            "action" => route('messages.update', [$support->id]),
            "method" => "PUT",
        ];
        return view('pages.support_message.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $support = SupportRequest::findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        $message = $validated['message'];

        $sentReply = event(new ReplyEmailEvent($support, $message));
        if ($sentReply) {
            $support->update([
                'reply_message' => $message,
                'status' => 'answered',
            ]);
            return redirect()->back()->with('success', 'Replied SuccessFully!');
        } else {
            return redirect()->back()->with('error', 'error');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
