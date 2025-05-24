<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function show($id)
    {
        $ticket = Ticket::with('replies')->findOrFail($id);

        return view('admin.tickets.replies.show', compact('ticket'));
    }

    public function store(Request $request, $id)
    {

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            [
                'attachment.max' => 'Gambar tidak boleh lebih dari 2mb.',
                'attachment.mimes' => 'Gambar harus dalam format jpg, jpeg, atau png.',
            ]
        ]);

        $ticket = Ticket::findOrFail($id);

        // Handle file upload if needed
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ticket_attachments', $filename, 'public');
        } else {
            $path = null;
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment' => $path,
        ]);

        return redirect()->back()->with('success', 'Tiket berhasil dibalas!');
    }

    public function close(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status' => 'closed',
        ]);

        return redirect()->back()->with('success', 'Tiket berhasil ditutup!');
    }
}
