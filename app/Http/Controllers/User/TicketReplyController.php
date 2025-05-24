<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function show($ticket_id)
    {
        $user = auth()->id();
        $ticket = Ticket::with('replies')->findOrFail($ticket_id);

        return view('user.tickets.replies.show', compact('ticket', 'user'));
    }

    public function store(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'attachment.max' => 'Gambar tidak boleh lebih dari 2MB.',
            'attachment.mimes' => 'Gambar harus dalam format JPG, JPEG, atau PNG.',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        // Upload file jika ada
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ticket_attachments', $filename, 'public');
        } else {
            $path = null;
        }

        // Simpan balasan
        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment' => $path,
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim!');
    }
}
