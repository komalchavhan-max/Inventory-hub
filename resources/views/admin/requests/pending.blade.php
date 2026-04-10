@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4">Pending Requests</h4>
        </div>
    </div>
    
    <!-- Equipment Requests -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Equipment Requests</h5>
                </div>
                <div class="card-body">
                    @if($equipmentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Equipment</th>
                                    <th>Priority</th>
                                    <th>Reason</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipmentRequests as $req)
                                <tr>
                                    <td>{{ $req->user->name }}</td>
                                    <td>{{ $req->equipment->name }}</td>
                                    <td><span class="badge bg-{{ $req->getPriorityColor() }}">{{ $req->priority }}</span></td>
                                    <td>{{ Str::limit($req->request_reason, 50) }}</td>
                                    <td>{{ $req->request_date->format('d-m-Y') }}</td>
                                    <td>
                                        <form action="{{ route('admin.requests.equipment.approve', $req->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">Reject</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No pending equipment requests</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection