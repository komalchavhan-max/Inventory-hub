@extends('layouts.admin')

@section('title', 'Maintenance Logs')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Maintenance & Repair History</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="maintenanceTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Equipment</th>
                            <th>Issue Description</th>
                            <th>Cost</th>
                            <th>Technician</th>
                            <th>Repair Date</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                {{ $log->equipment->name ?? 'N/A' }}<br>
                                <small class="text-muted">SN: {{ $log->equipment->serial_number ?? 'N/A' }}</small>
                            </td>
                            <td>{{ Str::limit($log->issue_description, 60) }}</td>
                            <td>${{ number_format($log->cost, 2) }}</td>
                            <td>{{ $log->technician_name }}</td>
                            <td>{{ $log->repair_date->format('d-m-Y') }}</td>
                            <td>{{ $log->created_at->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('admin.maintenance-logs.show', $log->id) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No maintenance logs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#maintenanceTable').DataTable({
            pageLength: 10,
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
        });
    });
</script>
@endpush