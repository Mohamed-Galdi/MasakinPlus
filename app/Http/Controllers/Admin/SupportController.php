<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['messages', 'user'])
            ->orderBy('last_message_at', 'desc')->paginate(10);

        return inertia('Admin/Support/index', compact('tickets'));
    }
}
