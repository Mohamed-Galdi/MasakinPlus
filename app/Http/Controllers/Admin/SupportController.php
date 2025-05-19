<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {

        $tickets = Ticket::with(['messages', 'user'])
            ->whereHas('user', function ($query) {
                $query->whereNot('type', UserType::Admin->value)
                    ->where('is_active', true);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('subject', 'like', '%'.$request->search.'%')
                        ->orWhereHas('user', function ($userQuery) use ($request) {
                            $userQuery->where('name', 'like', '%'.$request->search.'%')
                                ->whereNot('type', UserType::Admin->value);
                        });
                });
            })
            ->when($request->userType, function ($query) use ($request) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('type', $request->userType);
                });
            })
            ->orderBy('last_message_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $users = User::where('type', '!=', UserType::Admin->value)
            ->where('is_active', true)
            ->select('id', 'name')
            ->get();

        $usersTypes = array_values(UserType::optionsExcluding(UserType::Admin));
        $search = $request->get('search') ?? '';
        $userType = $request->get('userType') ?? '';

        return inertia('Admin/Support/index', compact('tickets', 'users', 'usersTypes', 'search', 'userType'));
    }

    public function create(Request $request)
    {

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'users_id' => 'required|array',
        ]);

        foreach ($request->users_id as $userId) {
            $ticket = new Ticket;

            $ticket->user_id = $userId;
            $ticket->subject = $request->subject;
            // $ticket->status = 'open';
            $ticket->save();

            $ticket->messages()->create([
                'sender' => 'admin',
                'content' => $request->content,
            ]);
        }

        return back();
    }

    public function resetUnreadCount($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $ticket->resetUnreadCount();
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
            'sender' => 'admin',
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
}
