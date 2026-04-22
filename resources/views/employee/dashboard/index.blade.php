@extends('layouts.employee')

@section('title', 'Dashboard')

@section('content')
<div class="welcome-banner">
    <div>
        <h4>Welcome back, {{ Auth::user()->name }}!</h4>
        <p>Manage your equipment and track your requests from here.</p>
    </div>
    <div class="welcome-icon d-none d-sm-grid">
        <i class="bi bi-box-seam"></i>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('employee.requests.equipment.form') }}" class="action-tile">
            <div class="tile-icon tint-primary"><i class="bi bi-laptop"></i></div>
            <h6>Request Equipment</h6>
            <p>Need new equipment?</p>
            <span class="tile-cta">Request now <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('employee.requests.exchange.form') }}" class="action-tile">
            <div class="tile-icon tint-info"><i class="bi bi-arrow-repeat"></i></div>
            <h6>Exchange Equipment</h6>
            <p>Exchange old for new.</p>
            <span class="tile-cta">Exchange now <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('employee.requests.repair.form') }}" class="action-tile">
            <div class="tile-icon tint-danger"><i class="bi bi-tools"></i></div>
            <h6>Report Repair</h6>
            <p>Something broken?</p>
            <span class="tile-cta">Report now <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('employee.requests.return.form') }}" class="action-tile">
            <div class="tile-icon tint-warning"><i class="bi bi-arrow-return-left"></i></div>
            <h6>Return Equipment</h6>
            <p>Done with an item?</p>
            <span class="tile-cta">Return now <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Equipment</h5>
                @if($myEquipment->count() > 0)
                    <span class="count-chip tint-primary">Assigned <span class="count-value">{{ $myEquipment->count() }}</span></span>
                @endif
            </div>
            <div class="card-body p-0">
                @if($myEquipment->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Equipment</th>
                                    <th>Serial Number</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myEquipment as $item)
                                <tr>
                                    <td class="fw-medium">{{ $item->name }}</td>
                                    <td class="text-muted">{{ $item->serial_number }}</td>
                                    <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 px-3">
                        <div class="tile-icon tint-slate mx-auto mb-3" style="width:48px;height:48px;">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <p class="text-muted mb-3">No equipment assigned to you yet.</p>
                        <a href="{{ route('employee.requests.equipment.form') }}" class="btn btn-primary btn-sm">Request Equipment</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-0">
            <div class="card-header">
                <h5 class="mb-0">My Recent Requests</h5>
            </div>
            <div class="card-body p-0">
                @if($recentRequests->count() > 0)
                    @php
                        $statusMap = [
                            'Pending'   => 'tint-warning',
                            'Approved'  => 'tint-info',
                            'Completed' => 'tint-success',
                            'Fulfilled' => 'tint-success',
                            'Rejected'  => 'tint-danger',
                        ];
                    @endphp
                    <div class="recent-requests">
                        @foreach($recentRequests as $request)
                            @php
                                $status = $request->status ?? 'Pending';
                                $cls = $statusMap[$status] ?? 'tint-slate';
                                if (isset($request->equipment) && $request->equipment) {
                                    $label = $request->equipment->name;
                                } elseif (isset($request->requestedEquipment) && $request->requestedEquipment) {
                                    $label = $request->requestedEquipment->name;
                                } else {
                                    $label = ucfirst(str_replace('_', ' ', $request->type ?? 'Request'));
                                }
                            @endphp
                            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom" style="border-color: var(--ih-border-soft) !important;">
                                <div class="d-flex align-items-center gap-3 min-width-0">
                                    <div>
                                        <div class="fw-medium">{{ $label }}</div>
                                        <div class="small text-muted">{{ $request->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <span class="badge-pill {{ $cls }}">{{ $status }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 px-3">
                        <div class="tile-icon tint-slate mx-auto mb-3" style="width:48px;height:48px;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <p class="text-muted mb-3">No recent requests found.</p>
                        <a href="{{ route('employee.requests.equipment.form') }}" class="btn btn-primary btn-sm">Make a Request</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
