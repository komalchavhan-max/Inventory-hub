@extends('layouts.admin')

@section('content')
<div class="card mb-0">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0">Categories List</h5>
            {{-- Optional: Add category filter dropdown if needed --}}
            {{-- You can create similar filter for active/inactive categories --}}
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle" id="categoriesTable" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Equipment Count</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@include('components.data-table.categories-table')