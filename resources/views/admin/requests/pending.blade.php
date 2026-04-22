@extends('layouts.admin')

@section('title', 'Pending Requests')

@section('content')
<h4 class="mb-3">Pending Requests</h4>

<div class="card mb-0">
    <div class="card-header">
        <h5 class="mb-0">Equipment Requests</h5>
    </div>
    <div class="card-body p-0">
        @if($equipmentRequests->count() > 0)
            @php
                $priorityMap = [
                    'Urgent' => 'tint-danger',
                    'High'   => 'tint-warning',
                    'Normal' => 'tint-info',
                    'Low'    => 'tint-slate',
                ];
            @endphp
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Equipment</th>
                            <th>Priority</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipmentRequests as $req)
                            @php $pcls = $priorityMap[$req->priority] ?? 'tint-slate'; @endphp
                            <tr>
                                <td class="fw-medium">{{ $req->user->name }}</td>
                                <td>{{ $req->equipment->name }}</td>
                                <td><span class="badge-pill {{ $pcls }}">{{ $req->priority }}</span></td>
                                <td class="text-muted">{{ Str::limit($req->request_reason, 50) }}</td>
                                <td class="text-muted">{{ $req->request_date->format('d-m-Y') }}</td>
                                <td class="text-end">
                                    <div class="action-group">
                                        <form action="{{ route('admin.requests.equipment.approve', $req->id) }}" method="POST" class="d-inline-flex">
                                            @csrf
                                            <button type="submit" class="action-btn approve" title="Approve" aria-label="Approve"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <button type="button" class="action-btn reject" title="Reject" aria-label="Reject"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted p-4 mb-0">No pending equipment requests</p>
        @endif
    </div>
</div>
@endsection
