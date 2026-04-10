@extends('layouts.admin')

@section('title', 'Equipment Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Equipment Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr><th width="35%">Name</th><td>{{ $equipment->name }}</td></tr>
                        <tr><th>Serial Number</th><td>{{ $equipment->serial_number }}</td></tr>
                        <tr><th>Category</th><td>{{ $equipment->category }}</td></tr>
                        <tr><th>Status</th><td><span class="badge bg-success">{{ $equipment->status }}</span></td></tr>
                        <tr><th>Condition</th><td>{{ $equipment->condition ?? 'Not specified' }}</td></tr>
                        <tr><th>Assigned To</th><td>{{ $equipment->assignedUser->name ?? 'Not Assigned' }}</td></tr>
                        <tr><th>Purchase Date</th><td>{{ $equipment->purchase_date ?? 'Not specified' }}</td></tr>
                        <tr><th>Warranty Until</th><td>{{ $equipment->warranty_until ?? 'Not specified' }}</td></tr>
                        <tr><th>Description</th><td>{{ $equipment->description ?? 'No description' }}</td></tr>
                        <tr><th>Specifications</th><td><pre>{{ $equipment->specifications ?? 'No specifications' }}</pre></td></tr>
                    </table>
                </div>
            </div>
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('admin.equipment.edit', $equipment->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection