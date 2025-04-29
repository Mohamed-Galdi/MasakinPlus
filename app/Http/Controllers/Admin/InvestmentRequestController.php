<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvestmentRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\User;
use Illuminate\Http\Request;

class InvestmentRequestController extends Controller
{
    public function index(Request $request)
    {
        $investmentRequests = InvestmentRequest::with('property.owner')
            ->when($request->statusFilter, function ($query) use ($request) {
                $query->where('status',  $request->statusFilter);
            })
            ->when($request->search, function ($query) use ($request) {
                // Owner filter
                $query->whereHas('property.owner', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        $statusFilter = $request->get('statusFilter') ?? '';
        $search = $request->get('search') ?? '';

        $investmentRequestsStatusOptions = InvestmentRequestStatus::options();
        return inertia('Admin/InvestmentRequests/index', compact('investmentRequests', 'investmentRequestsStatusOptions', 'statusFilter', 'search'));
    }
}
