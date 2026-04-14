@extends('layouts.admin')

@section('title', 'Equipment Requests')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Equipment Requests</h5>
            <div>
                <span class="badge bg-warning p-2">Pending: {{ $pendingCount ?? 0 }}</span>
                <span class="badge bg-success p-2">Fulfilled: {{ $fulfilledCount ?? 0 }}</span>
                <span class="badge bg-danger p-2">Rejected: {{ $rejectedCount ?? 0 }}</span>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="equipmentRequestsTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Equipment</th>
                            <th>Priority</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Admin Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->user->name ?? 'N/A' }}<br><small>{{ $req->user->email ?? '' }}</small></td>
                            <td>{{ $req->equipment->name ?? 'N/A' }}<br><small>SN: {{ $req->equipment->serial_number ?? 'N/A' }}</small></td>
                            <td>
                                @if($req->priority == 'Urgent')
                                    <span class="badge bg-danger">Urgent</span>
                                @elseif($req->priority == 'Normal')
                                    <span class="badge bg-warning">Normal</span>
                                @else
                                    <span class="badge bg-info">Low</span>
                                @endif
                            </td>
                            <td>{{ $req->request_date->format('d-m-Y') }}</td>
                            <td>
                                @if($req->status == 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($req->status == 'Approved')
                                    <span class="badge bg-info">Approved</span>
                                @elseif($req->status == 'Fulfilled')
                                    <span class="badge bg-success">Fulfilled</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($req->admin_message)
                                    <span class="badge bg-info">Message Sent</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($req->status == 'Pending')
                                    <form action="{{ route('admin.requests.equipment.approve', $req->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">Reject</button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No requests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modals -->
@foreach($requests as $req)
@if($req->status == 'Pending')
<div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.requests.equipment.reject', $req->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Request #{{ $req->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea name="rejection_message" class="form-control" rows="4" required placeholder="Reason for rejection..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#equipmentRequestsTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'desc']],
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            columnDefs: [
                { orderable: false, targets: [7] }
            ]
        });
    });
</script>
@endpush