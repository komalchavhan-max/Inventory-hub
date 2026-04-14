@extends('layouts.admin')

@section('title', 'Repair Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Repair Requests</h5>
                    <div>
                        <span class="badge bg-warning p-2">Pending: {{ $pendingCount ?? 0 }}</span>
                        <span class="badge bg-info p-2">Approved: {{ $approvedCount ?? 0 }}</span>
                        <span class="badge bg-success p-2">Completed: {{ $completedCount ?? 0 }}</span>
                        <span class="badge bg-danger p-2">Rejected: {{ $rejectedCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="repairRequestsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Equipment</th>
                                    <th>Issue</th>
                                    <th>Urgency</th>
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
                                    <td>{{ $req->equipment->name ?? 'N/A' }}<br><small>{{ $req->equipment->serial_number ?? '' }}</small></td>
                                    <td>{{ Str::limit($req->issue_description, 50) }}</td>
                                    <td>
                                        @if($req->urgency == 'Critical')
                                            <span class="badge bg-danger">Critical</span>
                                        @elseif($req->urgency == 'High')
                                            <span class="badge bg-warning">High</span>
                                        @elseif($req->urgency == 'Medium')
                                            <span class="badge bg-info">Medium</span>
                                        @else
                                            <span class="badge bg-success">Low</span>
                                        @endif
                                    </td>
                                    <td>{{ $req->request_date->format('d-m-Y') }}</td>
                                    <td>
                                        @if($req->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($req->status == 'Approved')
                                            <span class="badge bg-info">In Repair</span>
                                        @elseif($req->status == 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($req->status == 'Rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @if($req->admin_message)
                                                <button type="button" class="btn btn-sm btn-link text-danger" data-bs-toggle="modal" data-bs-target="#messageModal{{ $req->id }}">
                                                    View Reason
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->admin_message)
                                            <span class="badge bg-info">Sent</span>
                                            <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#detailModal{{ $req->id }}">
                                                Read
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->status == 'Pending')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.requests.repair.approve', $req->id) }}" method="POST" style="display:inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                                                    Reject
                                                </button>
                                            </div>
                                        @elseif($req->status == 'Approved')
                                            <form action="{{ route('admin.requests.repair.complete', $req->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Mark Complete</button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.requests.repair.reject', $req->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Reject Repair Request #{{ $req->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Employee:</strong> {{ $req->user->name ?? 'N/A' }}</p>
                                                    <p><strong>Equipment:</strong> {{ $req->equipment->name ?? 'N/A' }}</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                        <textarea name="rejection_message" class="form-control" rows="4" required 
                                                            placeholder="Please explain why this repair request is being rejected..."></textarea>
                                                        <small class="text-muted">This message will be visible to both admin and employee.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Send & Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Message Detail Modal -->
                                @if($req->admin_message)
                                <div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Rejection Message for Repair Request #{{ $req->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Employee:</strong> {{ $req->user->name ?? 'N/A' }}</p>
                                                <p><strong>Rejected on:</strong> {{ $req->updated_at->format('d-m-Y H:i') }}</p>
                                                <hr>
                                                <p><strong>Rejection Reason:</strong></p>
                                                <div class="alert alert-danger">
                                                    {{ $req->admin_message }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal fade" id="messageModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Rejection Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Reason:</strong></p>
                                                <div class="alert alert-danger">
                                                    {{ $req->admin_message }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No repair requests found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#repairRequestsTable').DataTable({
            pageLength: 10,
            order: [[0, 'desc']],
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [8] }
            ]
        });
    });
</script>
@endpush
@endsection