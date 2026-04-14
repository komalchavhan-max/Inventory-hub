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
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="equipmentTable">
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
                            <td>
                                {{ $item->name }}
                                @if($item->status == 'Archived')
                                    <span class="badge bg-secondary ms-2">Archived</span>
                                @endif
                            </td>
                            <td>{{ $item->serial_number }}</td>
                            <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                            <td>
                                @if($item->status == 'Available')
                                    <span class="badge bg-success">Available</span>
                                @elseif($item->status == 'Assigned')
                                    <span class="badge bg-warning">Assigned</span>
                                @elseif($item->status == 'In-Repair')
                                    <span class="badge bg-danger">In Repair</span>
                                @elseif($item->status == 'Archived')
                                    <span class="badge bg-secondary">Archived</span>
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
                                @if($item->status != 'Archived')
                                    <a href="{{ route('admin.equipment.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.equipment.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.equipment.destroy', $item->id) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this equipment? It will be hidden from employees.')">Archive</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.equipment.restore', $item->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Restore this equipment? It will become visible to employees.')">Restore</button>
                                    </form>
                                @endif
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
@push('scripts')
<script>
    $(document).ready(function() {
        $('#equipmentTable').DataTable({
            pageLength: 10,
            order: [[0, 'desc']],
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });
</script>
@endpush
@endsection