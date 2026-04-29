@extends('layouts.admin')

@section('content')
<div class="welcome-banner">
    <div>
        <h4>Welcome back, {{ Auth::user()->name }}!</h4>
        <p>Here's what's happening with your inventory today.</p>
    </div>
    <div class="welcome-icon d-none d-sm-grid">
        <i class="bi bi-graph-up-arrow"></i>
    </div>
</div>

<div class="row g-3">
    <!-- Total Equipment -->
    <div class="col-md-2 col-sm-4 col-6">
         <a href="{{ route('admin.equipment.index', ['status' => 'All Status']) }}" class="text-decoration-none">
            <div class="card stat-card text-center p-3">
                <div class="stat-value fw-bold fs-2 ">{{ $totalEquipment ?? 0 }}</div>
                 <div class="stat-label text-muted small">Total Equipment</div>
            </div>
        </a>
    </div>
    
    <!-- Available -->
    <div class="col-md-2 col-sm-4 col-6">
        <a href="{{ route('admin.equipment.index', ['status' => 'Available']) }}" class="text-decoration-none">
            <div class="card stat-card text-center p-3">
                <div class="stat-value fw-bold fs-2 text-success">{{ $available ?? 0 }}</div>
                <div class="stat-label text-muted small">Available</div>
            </div>
        </a>
    </div>
    
    <!-- Assigned -->
    <div class="col-md-2 col-sm-4 col-6">
        <a href="{{ route('admin.equipment.index', ['status' => 'Assigned']) }}" class="text-decoration-none">
            <div class="card stat-card text-center p-3">
                <div class="stat-value fw-bold fs-2 text-warning">{{ $assigned ?? 0 }}</div>
                <div class="stat-label text-muted small">Assigned</div>
            </div>
        </a>
    </div>
    
    <!-- In Repair  -->
    <div class="col-md-2 col-sm-4 col-6">
        <a href="{{ route('admin.equipment.index', ['status' => 'In-Repair']) }}" class="text-decoration-none">
            <div class="card stat-card text-center p-3">
                <div class="stat-value fw-bold fs-2 text-danger">{{ $inRepair ?? 0 }}</div>
                <div class="stat-label text-muted small">In Repair</div>
            </div>
        </a>
    </div>
    
    <!-- Archived -->
    <div class="col-md-2 col-sm-4 col-6">
        <a href="{{ route('admin.equipment.index', ['status' => 'Archived']) }}" class="text-decoration-none">
            <div class="card stat-card text-center p-3">
                <div class="stat-value fw-bold fs-2 text-secondary">{{ $archived ?? 0 }}</div>
                <div class="stat-label text-muted small">Archived</div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-7">
        <div class="card mb-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Recent Equipment Added</h5>
                <a href="{{ route('admin.equipment.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle">
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
                                @php
                                    $statusMap = [
                                        'All Status' => ['tint-success', 'All Status'],
                                        'Available' => ['tint-success', 'Available'],
                                        'Assigned'  => ['tint-warning', 'Assigned'],
                                        'In-Repair' => ['tint-danger',  'In Repair'],
                                        'Archived'  => ['tint-slate',   'Archived'],
                                    ];
                                    [$cls, $lbl] = $statusMap[$item->status] ?? ['tint-slate', $item->status];
                                @endphp
                                <tr>
                                    <td class="fw-medium">{{ $item->name }}</td>
                                    <td class="text-muted">{{ $item->serial_number }}</td>
                                    <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                                    <td><span class="badge-pill {{ $cls }}">{{ $lbl }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No equipment found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-0">
            <div class="card-header">
                <h5 class="mb-0">Pending Requests</h5>
            </div>
            <div class="card-body p-0">
                @php
                    $pendingRows = [
                        ['Equipment Requests', $pendingEquipmentRequests ?? 0, 'bi-inbox',              'tint-primary', route('admin.requests.equipment')],
                        ['Exchange Requests',  $pendingExchangeRequests  ?? 0, 'bi-arrow-repeat',       'tint-info',    route('admin.requests.exchange')],
                        ['Repair Requests',    $pendingRepairRequests    ?? 0, 'bi-tools',              'tint-danger',  route('admin.requests.repair')],
                        ['Return Requests',    $pendingReturnRequests    ?? 0, 'bi-arrow-return-left',  'tint-warning', route('admin.requests.return')],
                    ];
                @endphp
                @foreach($pendingRows as [$label, $count, $icon, $cls, $url])
                    <a href="{{ $url }}" class="pending-item">
                        <span class="label">
                            <span class="label-icon {{ $cls }}"><i class="bi {{ $icon }}"></i></span>
                            {{ $label }}
                        </span>
                        <span class="pending-count {{ $count == 0 ? 'zero' : '' }}">{{ $count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
