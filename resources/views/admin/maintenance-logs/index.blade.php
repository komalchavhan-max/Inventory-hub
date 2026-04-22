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
                <table class="table table-bordered" id="maintenanceLogsTable">
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    $('#maintenanceLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url: '{{ url("/admin/maintenance-logs-data") }}',
            type: 'GET',
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'equipment_name', name: 'equipment_name' },
            { data: 'issue_description', name: 'issue_description' },
            { data: 'cost', name: 'cost' },
            { data: 'technician_name', name: 'technician_name' },
            { data: 'repair_date', name: 'repair_date' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        pageLength: 10,
        order: [[0, 'desc']],
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