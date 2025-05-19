<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\Property;
use Illuminate\Http\Request;

class InvestmentRequestController extends Controller
{
    public function index(Request $request)
    {
        $investmentRequests = InvestmentRequest::with('property.owner', 'property.images')
            ->when($request->statusFilter, function ($query) use ($request) {
                $query->where('status', $request->statusFilter);
            })
            ->when($request->search, function ($query) use ($request) {
                // Owner filter
                $query->whereHas('property.owner', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $statusFilter = $request->get('statusFilter') ?? '';
        $search = $request->get('search') ?? '';

        $investmentRequestsStatusOptions = InvestmentRequestStatus::options();

        return inertia('Admin/InvestmentRequests/index', compact('investmentRequests', 'investmentRequestsStatusOptions', 'statusFilter', 'search'));
    }

    public function reply(Request $request)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1500',
        ]);

        $investmentRequest = InvestmentRequest::find($request->request_id);

        $property = Property::find($investmentRequest->property_id);

        if ($request->reply === 'approve') {
            $investmentRequest->status = InvestmentRequestStatus::Approved;
            $property->status = PropertyStatus::PreparingInvestmentOffer;
        } elseif ($request->reply === 'reject') {
            $investmentRequest->status = InvestmentRequestStatus::Rejected;
            $property->status = PropertyStatus::InvestmentRejected;
        }

        $investmentRequest->admin_note = $request->admin_note;
        $investmentRequest->save();
        $property->save();

        return back();
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'new_status' => 'required|string|max:1500',
            'admin_note' => 'required|string|max:1500',
            'send_notification' => 'boolean',
        ]);

        $investmentRequest = InvestmentRequest::find($request->request_id);

        $property = Property::find($investmentRequest->property_id);

        if ($request->new_status === InvestmentRequestStatus::Approved->value) {
            $investmentRequest->status = InvestmentRequestStatus::Approved->value;
            $property->status = PropertyStatus::PreparingInvestmentOffer->value;
        } elseif ($request->new_status === InvestmentRequestStatus::Rejected->value) {
            $investmentRequest->status = InvestmentRequestStatus::Rejected->value;
            $property->status = PropertyStatus::InvestmentRejected->value;
        } elseif ($request->new_status === InvestmentRequestStatus::Pending->value) {
            $investmentRequest->status = InvestmentRequestStatus::Pending->value;
            $property->status = PropertyStatus::Draft->value;
        }

        $investmentRequest->admin_note = $request->admin_note;
        $investmentRequest->save();
        $property->save();

        return back();
    }
}
