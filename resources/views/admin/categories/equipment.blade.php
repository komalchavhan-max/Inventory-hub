@extends('layouts.admin')
@section('content')
@php
    $statusMap = [
        'Available' => 'tint-success',
        'Assigned'  => 'tint-warning',
        'In-Repair' => 'tint-danger',
        'Archived'  => 'tint-slate',
    ];
    $conditionMap = ['New' => 'tint-info', 'Good' => 'tint-success', 'Fair' => 'tint-warning', 'Poor' => 'tint-danger'];
@endphp

<div class="card mb-3">
    <div class="card-body d-flex align-items-center gap-3">
        <div class="stat-icon tint-primary" style="width:52px;height:52px;font-size:1.4rem;">
            @if($category->icon)
                <i class="{{ $category->icon }}"></i>
            @else
                <i class="bi bi-tag"></i>
            @endif
        </div>
        <div>
            <h4 class="mb-1">{{ $category->name }}</h4>
            <p class="mb-0 text-muted">{{ $category->description ?? 'No description available' }}</p>
        </div>
    </div>
</div>

<div class="card mb-0">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <h5 class="mb-0">Equipment in {{ $category->name }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Categories
            </a>
            <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Equipment
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Filter by category</label>
                <select class="form-select form-select-sm" onchange="window.location.href=this.value">
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

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Serial Number</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th>Assigned To</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                        @php
                            $statusCls = $statusMap[$item->status] ?? 'tint-slate';
                            $statusLabel = $item->status === 'In-Repair' ? 'In Repair' : $item->status;
                            $conditionCls = $conditionMap[$item->condition] ?? 'tint-slate';
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $item->id }}</td>
                            <td class="fw-medium">{{ $item->name }}</td>
                            <td class="text-muted">{{ $item->serial_number }}</td>
                            <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                            <td><span class="badge-pill {{ $statusCls }}">{{ $statusLabel }}</span></td>
                            <td><span class="badge-pill {{ $conditionCls }}">{{ $item->condition }}</span></td>
                            <td>{{ $item->assignedUser->name ?? 'Not Assigned' }}</td>
                            <td class="text-end">
                                <div class="action-group">
                                    <a href="{{ route('admin.equipment.show', $item->id) }}" class="action-btn view" title="View" aria-label="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.equipment.edit', $item->id) }}" class="action-btn edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-2">No equipment found in this category.</p>
                                <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary btn-sm">Add Equipment</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
