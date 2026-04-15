@extends('layouts.admin')

@section('title', 'Maintenance Log Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Maintenance Log Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr><th>Equipment</th><td>{{ $log->equipment->name ?? 'N/A' }} ({{ $log->equipment->serial_number ?? 'N/A' }})</td></tr>
                        <tr><th>Issue Description</th><td>{{ $log->issue_description }}</td></tr>
                        <tr><th>Cost</th><td>${{ number_format($log->cost, 2) }}</td></tr>
                        <tr><th>Technician Name</th><td>{{ $log->technician_name }}</td></tr>
                        <tr><th>Repair Date</th><td>{{ $log->repair_date->format('d-m-Y') }}</td></tr>
                        <tr><th>Created At</th><td>{{ $log->created_at->format('d-m-Y H:i') }}</td></tr>
                        <tr><th>Last Updated</th><td>{{ $log->updated_at->format('d-m-Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>
            <a href="{{ route('admin.maintenance-logs.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection