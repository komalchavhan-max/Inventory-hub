@extends('layouts.admin')

@section('content')
<div class="card mb-0">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0">Equipment List</h5>
        </div>
        <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Equipment
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle" id="equipmentTable" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th>Assigned To</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@include('components.data-table.equipment-table')
