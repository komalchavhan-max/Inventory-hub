@extends('layouts.employee')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 opacity-75">Manage your equipment and requests from here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <iconify-icon icon="solar:computer-line-duotone" class="fs-1 text-primary"></iconify-icon>
                    <h5 class="mt-2">Request Equipment</h5>
                    <p class="small text-muted">Need new equipment?</p>
                    <a href="{{ route('employee.requests.equipment.form') }}" class="btn btn-primary btn-sm">
                        Request Now
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <iconify-icon icon="solar:repeat-line-duotone" class="fs-1 text-warning"></iconify-icon>
                    <h5 class="mt-2">Exchange Equipment</h5>
                    <p class="small text-muted">Exchange old for new</p>
                    <a href="{{ route('employee.requests.exchange.form') }}" class="btn btn-warning btn-sm">
                        Exchange Now
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <iconify-icon icon="solar:tools-line-duotone" class="fs-1 text-danger"></iconify-icon>
                    <h5 class="mt-2">Report Repair</h5>
                    <p class="small text-muted">Equipment broken?</p>
                    <a href="{{ route('employee.requests.repair.form') }}" class="btn btn-danger btn-sm">
                        Report Now
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <iconify-icon icon="solar:undo-left-line-duotone" class="fs-1 text-info"></iconify-icon>
                    <h5 class="mt-2">Return Equipment</h5>
                    <p class="small text-muted">Return equipment</p>
                    <a href="{{ route('employee.requests.return.form') }}" class="btn btn-info btn-sm">
                        Return Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- My Equipment Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Equipment</h5>
                </div>
                <div class="card-body">
                    @if($myEquipment->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
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
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->category }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No equipment assigned to you yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Recent Requests</h5>
                </div>
                <div class="card-body">
                    @if($recentRequests->count() > 0)
                    <div class="list-group">
                        @foreach($recentRequests as $request)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $request->type ?? 'Request' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() ?? 'Recently' }}</small>
                                </div>
                                <span class="badge bg-warning">{{ $request->status ?? 'Pending' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No recent requests.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection