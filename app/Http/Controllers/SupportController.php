<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        
        $tickets = Ticket::with(['messages'])
            ->where('user_id', auth()->id())  // Get only tickets for the authenticated user
            ->when($request->search, function ($query) use ($request) {
                $query->where('subject', 'like', '%' . $request->search . '%');
            })
            ->orderBy('last_message_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $search = $request->get('search') ?? '';

        return inertia('Common/support', compact('tickets'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
        ]);

        $ticket = new Ticket;
        $ticket->user_id = auth()->id();
        $ticket->subject = $request->subject;
        $ticket->status = 'open';
        $ticket->save();

        $ticket->messages()->create([
            'sender' => 'user',
            'content' => $request->content,
        ]);

        return redirect()->route('support.index');
    }

    public function resetUserUnreadCount($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $ticket->resetUserUnreadCount();
        $ticket->save();

        return back();
    }

    public function reply(Request $request, $ticketId)
    {
        $request->validate([
            'content' => 'required|max:1500',
        ]);
        $ticket = Ticket::find($ticketId);
        $ticket->messages()->create([
            'sender' => 'user',
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
}
