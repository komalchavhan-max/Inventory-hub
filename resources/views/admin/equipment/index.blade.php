@extends('layouts.admin')

@section('title', 'Equipment Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Equipment List</h5>
            <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary">+ Add Equipment</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="equipmentTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Serial Number</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Condition</th>
                            <th>Assigned To</th>
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
$(document).ready(function() {
    $('#equipmentTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/admin/equipment-data") }}',
            type: 'GET',
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'serial_number', name: 'serial_number' },
            { data: 'category_name', name: 'category_name' },
            { data: 'status', name: 'status' },
            { data: 'condition', name: 'condition' },
            { data: 'assigned_to_name', name: 'assigned_to_name' },
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