@extends('layouts.admin')

@section('title', 'Equipment Management')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Equipment List</h5>
            <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary">+ Add Equipment</a>
        </div>
        <div class="card-body">
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
                            <td>{{ $item->category }}</td>
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
                                <a href="{{ route('admin.equipment.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.equipment.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.equipment.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this equipment?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No equipment found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection