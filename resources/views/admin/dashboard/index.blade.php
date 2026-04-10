@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 opacity-75">Here's what's happening with your inventory today.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total Equipment</h6>
                            <h3 class="mb-0">{{ $totalEquipment ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <iconify-icon icon="solar:computer-line-duotone" class="fs-4 text-primary"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Available</h6>
                            <h3 class="mb-0 text-success">{{ $available ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <iconify-icon icon="solar:check-circle-line-duotone" class="fs-4 text-success"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Assigned</h6>
                            <h3 class="mb-0 text-warning">{{ $assigned ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <iconify-icon icon="solar:user-line-duotone" class="fs-4 text-warning"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">In Repair</h6>
                            <h3 class="mb-0 text-danger">{{ $inRepair ?? 0 }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded p-3">
                            <iconify-icon icon="solar:tools-line-duotone" class="fs-4 text-danger"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Equipment and Pending Requests -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Equipment Added</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Serial Number</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentEquipment ?? [] as $item)
                                <tr>
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
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No equipment found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Requests</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Equipment Requests
                            <span class="badge bg-warning rounded-pill">{{ $pendingEquipmentRequests ?? 0 }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Exchange Requests
                            <span class="badge bg-warning rounded-pill">{{ $pendingExchangeRequests ?? 0 }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Repair Requests
                            <span class="badge bg-warning rounded-pill">{{ $pendingRepairRequests ?? 0 }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Return Requests
                            <span class="badge bg-warning rounded-pill">{{ $pendingReturnRequests ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection