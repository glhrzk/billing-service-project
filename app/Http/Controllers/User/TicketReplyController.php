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
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Tiket berhasil dibalas!');
    }
}
