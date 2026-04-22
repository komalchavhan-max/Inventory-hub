@extends('layouts.admin')

@section('title', 'Equipment Details')

@section('content')
@php
    $statusMap = [
        'Available' => 'tint-success',
        'Assigned'  => 'tint-warning',
        'In-Repair' => 'tint-danger',
        'Archived'  => 'tint-slate',
    ];
    $statusCls = $statusMap[$equipment->status] ?? 'tint-slate';
    $statusLabel = $equipment->status === 'In-Repair' ? 'In Repair' : $equipment->status;

    $conditionMap = ['New' => 'tint-info', 'Good' => 'tint-success', 'Fair' => 'tint-warning', 'Poor' => 'tint-danger'];
    $conditionCls = $equipment->condition ? ($conditionMap[$equipment->condition] ?? 'tint-slate') : null;
@endphp

<div class="card mb-0">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <h5 class="mb-0">Equipment Details</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('admin.equipment.edit', $equipment->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-7">
                <table class="table align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 35%;">Name</th>
                            <td>{{ $equipment->name }}</td>
                        </tr>
                        <tr>
                            <th>Serial Number</th>
                            <td class="text-muted">{{ $equipment->serial_number }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $equipment->category->name ?? 'Uncategorized' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><span class="badge-pill {{ $statusCls }}">{{ $statusLabel }}</span></td>
                        </tr>
                        <tr>
                            <th>Condition</th>
                            <td>
                                @if($conditionCls)
                                    <span class="badge-pill {{ $conditionCls }}">{{ $equipment->condition }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Assigned To</th>
                            <td>{{ $equipment->assignedUser->name ?? 'Not Assigned' }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Date</th>
                            <td class="text-muted">{{ $equipment->purchase_date ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Warranty Until</th>
                            <td class="text-muted">{{ $equipment->warranty_until ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $equipment->description ?? 'No description' }}</td>
                        </tr>
                        <tr>
                            <th class="align-top">Specifications</th>
                            <td>
                                @if($equipment->specifications)
                                    <pre class="mb-0 p-3 rounded" style="background: var(--ih-bg); border: 1px solid var(--ih-border-soft); font-size: 0.85rem;">{{ is_string($equipment->specifications) ? $equipment->specifications : json_encode($equipment->specifications, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-muted">No specifications</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
