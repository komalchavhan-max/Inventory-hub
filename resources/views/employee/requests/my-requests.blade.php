@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary bg-opacity-10">
                <div class="card-body">
                    <h4 class="mb-1">My Requests</h4>
                    <p class="text-muted mb-0">Track all your equipment, exchange, repair, and return requests</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#equipmentTab">
                        📦 Equipment Requests 
                        <span class="badge bg-secondary">{{ $equipmentRequests->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#exchangeTab">
                        🔄 Exchange Requests 
                        <span class="badge bg-secondary">{{ $exchangeRequests->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#repairTab">
                        🔧 Repair Requests 
                        <span class="badge bg-secondary">{{ $repairRequests->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#returnTab">
                        📤 Return Requests 
                        <span class="badge bg-secondary">{{ $returnRequests->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                
                <!-- Equipment Requests Tab -->
                <div class="tab-pane fade show active" id="equipmentTab">
                    <div class="card">
                        <div class="card-body">
                            @if($equipmentRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="equipmentRequestsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Equipment</th>
                                            <th>Priority</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Admin Response</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($equipmentRequests as $request)
                                        <tr>
                                             <td>{{ $request->equipment->name ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $request->equipment->serial_number ?? '' }}</small>
                                            </td>
                                            <td>
                                                @if($request->priority == 'Urgent')
                                                    <span class="badge bg-danger">Urgent</span>
                                                @elseif($request->priority == 'Normal')
                                                    <span class="badge bg-warning">Normal</span>
                                                @else
                                                    <span class="badge bg-info">Low</span>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($request->request_reason, 60) }}</td>
                                            <td>{{ $request->request_date->format('d-m-Y') }}</td>
                                            <td>
                                                @if($request->status == 'Pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($request->status == 'Approved')
                                                    <span class="badge bg-info">Approved</span>
                                                @elseif($request->status == 'Fulfilled')
                                                    <span class="badge bg-success">Fulfilled</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>{{ $request->admin_notes ?? '-' }}</td>
                                            <td>
                                                @if($request->status == 'Rejected' && $request->admin_message)
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eqRejectModal{{ $request->id }}">
                                                        View Reason
                                                    </button>
                                                @elseif($request->status == 'Approved' && $request->admin_message)
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#eqApproveModal{{ $request->id }}">
                                                        View Message
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                             @else
                            <div class="text-center py-5">
                                <p class="text-muted">No equipment requests found</p>
                                <a href="{{ route('employee.requests.equipment.form') }}" class="btn btn-primary">Request Equipment</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Exchange Requests Tab -->
                <div class="tab-pane fade" id="exchangeTab">
                    <div class="card">
                        <div class="card-body">
                            @if($exchangeRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="exchangeRequestsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Old Equipment</th>
                                            <th>Requested Equipment</th>
                                            <th>Reason</th>
                                            <th>Condition</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($exchangeRequests as $req)
                                        <tr>
                                            <td>{{ $req->oldEquipment->name ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $req->oldEquipment->serial_number ?? '' }}</small>
                                            </td>
                                            <td>{{ $req->requestedEquipment->name ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $req->requestedEquipment->serial_number ?? '' }}</small>
                                            </td>
                                            <td>{{ Str::limit($req->exchange_reason, 50) }}</td>
                                            <td>
                                                @if($req->has_damage)
                                                    <span class="badge bg-danger">Damaged</span>
                                                @else
                                                    <span class="badge bg-success">Good</span>
                                                @endif
                                            </td>
                                            <td>{{ $req->request_date->format('d-m-Y') }}</td>
                                            <td>
                                                @if($req->status == 'Pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($req->status == 'Approved')
                                                    <span class="badge bg-info">Approved</span>
                                                @elseif($req->status == 'Completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($req->status == 'Rejected' && $req->admin_message)
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exRejectModal{{ $req->id }}">
                                                        View Reason
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <p class="text-muted">No exchange requests found</p>
                                <a href="{{ route('employee.requests.exchange.form') }}" class="btn btn-warning">Request Exchange</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Repair Requests Tab -->
                <div class="tab-pane fade" id="repairTab">
                    <div class="card">
                        <div class="card-body">
                            @if($repairRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="repairRequestsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Equipment</th>
                                            <th>Issue</th>
                                            <th>Urgency</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($repairRequests as $req)
                                        <tr>
                                            <td>{{ $req->equipment->name ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $req->equipment->serial_number ?? '' }}</small>
                                            </td>
                                            <td>{{ Str::limit($req->issue_description, 60) }}</td>
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
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($req->status == 'Rejected' && $req->admin_message)
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#repRejectModal{{ $req->id }}">
                                                        View Reason
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <p class="text-muted">No repair requests found</p>
                                <a href="{{ route('employee.requests.repair.form') }}" class="btn btn-danger">Report Repair</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Return Requests Tab -->
                <div class="tab-pane fade" id="returnTab">
                    <div class="card">
                        <div class="card-body">
                            @if($returnRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="returnRequestsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Equipment</th>
                                            <th>Return Reason</th>
                                            <th>Condition</th>
                                            <th>Return Date</th>
                                            <th>Status</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($returnRequests as $req)
                                        <tr>
                                            <td>{{ $req->equipment->name ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $req->equipment->serial_number ?? '' }}</small>
                                            </td>
                                            <td>{{ $req->return_reason }}</td>
                                            <td>{{ Str::limit($req->equipment_condition, 40) }}</td>
                                            <td>{{ $req->return_date->format('d-m-Y') }}</td>
                                            <td>
                                                @if($req->status == 'Pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($req->status == 'Approved')
                                                    <span class="badge bg-info">Approved</span>
                                                @elseif($req->status == 'Completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($req->status == 'Rejected' && $req->admin_message)
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#retRejectModal{{ $req->id }}">
                                                        View Reason
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <p class="text-muted">No return requests found</p>
                                <a href="{{ route('employee.requests.return.form') }}" class="btn btn-info">Return Equipment</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Equipment Requests Table
        $('#equipmentRequestsTable').DataTable({
            pageLength: 10,
            order: [[3, 'desc']],
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
            }
        });
        
        // Exchange Requests Table
        $('#exchangeRequestsTable').DataTable({
            pageLength: 10,
            order: [[4, 'desc']],
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
            }
        });
        
        // Repair Requests Table
        $('#repairRequestsTable').DataTable({
            pageLength: 10,
            order: [[3, 'desc']],
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
            }
        });
        
        // Return Requests Table
        $('#returnRequestsTable').DataTable({
            pageLength: 10,
            order: [[3, 'desc']],
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
            }
        });
    });
</script>
@endpush

@endsection