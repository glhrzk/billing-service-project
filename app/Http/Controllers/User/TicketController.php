<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class TicketController extends Controller
{
    public function create()
    {
        return view('user.tickets.create');
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('user.tickets.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'description' => $request->message,
            'status' => 'open',
        ]);

        // Send notification to admin
        Notification::send(User::role('admin')->get(), new TicketCreatedNotification($ticket));

        return redirect()->back()->with('success', 'Tiket berhasil dikirim!');

    }

}
