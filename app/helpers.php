<?php
use App\Models\DocumentTracking;
use App\Models\Outgoing;

function receivedTotal()
{
    $query = DocumentTracking::where('status', 'received');

    if (auth()->user()->is_admin == 0) {
        $query->where('office_division', auth()->user()->office_division);
    }

    return $query->count();
}

function rejectedTotal()
{
    $query = DocumentTracking::where('status', 'rejected');

    if (auth()->user()->is_admin == 0) {
        $query->where('office_division', auth()->user()->office_division);
    }

    return $query->count();
}

function incomingTotal()
{
    $query = DocumentTracking::where('status', 'received'); // Assuming 'received' was meant to be 'incoming'

    if (auth()->user()->is_admin == 0) {
        $query->where('office_division', auth()->user()->office_division);
    }

    return $query->count();
}

function outgoingTotal()
{
    $query = Outgoing::with('user');

    if (auth()->user()->is_admin == 0) {
        $query->whereHas('user', function ($q) {
            $q->where('office_division', auth()->user()->office_division);
        });
    }

    return $query->count();
}
