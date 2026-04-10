@extends('layouts.admin')

@section('title', 'Equipment - ' . $category->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($category->icon)
                            <i class="{{ $category->icon }} fs-1 me-3"></i>
                        @else
                            <i class="bi bi-tag fs-1 me-3"></i>
                        @endif
                        <div>
                            <h4 class="mb-1">{{ $category->name }}</h4>
                            <p class="mb-0 text-muted">{{ $category->description ?? 'No description available' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Equipment in {{ $category->name }}</h5>
                    <div>
                        <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm">
                            <iconify-icon icon="solar:add-circle-line-duotone"></iconify-icon> Add Equipment
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                            <iconify-icon icon="solar:arrow-left-line-duotone"></iconify-icon> Back to Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter by Category Dropdown -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter by Category</label>
                            <select class="form-select" id="categoryFilter" onchange="window.location.href=this.value">
                                <option value="{{ route('admin.equipment.index') }}">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ url('/admin/categories/' . $cat->slug . '/equipment') }}" 
                                        {{ $cat->id == $category->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->equipment_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                @forelse($equipment as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                                    <td>
                                        @if($item->status == 'Available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($item->status == 'Assigned')
                                            <span class="badge bg-warning">Assigned</span>
                                        @else
                                            <span class="badge bg-danger">In Repair</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->condition == 'New')
                                            <span class="badge bg-primary">New</span>
                                        @elseif($item->condition == 'Good')
                                            <span class="badge bg-success">Good</span>
                                        @elseif($item->condition == 'Fair')
                                            <span class="badge bg-warning">Fair</span>
                                        @else
                                            <span class="badge bg-danger">Poor</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->assignedUser->name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <a href="{{ route('admin.equipment.show', $item->id) }}" class="btn btn-sm btn-info">
                                            <iconify-icon icon="solar:eye-line-duotone"></iconify-icon> View
                                        </a>
                                        <a href="{{ route('admin.equipment.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <iconify-icon icon="solar:pen-2-line-duotone"></iconify-icon> Edit
                                        </a>
                                     </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <p class="text-muted mb-0">No equipment found in this category.</p>
                                        <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm mt-2">Add Equipment</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection