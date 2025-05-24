<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $selectedStatus = $request->get('status');

        if ($selectedStatus) {
            // Filter tickets based on the selected status
            $tickets = Ticket::where('status', $selectedStatus)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Fetch all tickets if no status is selected
            $tickets = Ticket::orderBy('created_at', 'desc')->get();
        }

        // Return the view with the tickets data
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        // Fetch a specific ticket by ID
        $ticket = Ticket::findOrFail($id);

        // Return the view with the ticket data
        return view('admin.tickets.show', compact('ticket'));
    }
}
