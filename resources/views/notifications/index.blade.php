@extends('layouts.admin')

@section('title', 'My Notifications')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-bell me-2"></i> My Notifications
            </h5>
            <button class="btn btn-primary btn-sm" id="markAllReadPageBtn">
                <i class="bi bi-check2-all me-1"></i> Mark all as read
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="notificationsTable" width="100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Destroy existing DataTable if any
    if ($.fn.DataTable.isDataTable('#notificationsTable')) {
        $('#notificationsTable').DataTable().destroy();
    }
    
    $('#notificationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("notifications.data") }}',
            type: 'GET',
            error: function(xhr, error, code) {
                console.log('AJAX Error:', xhr.responseText);
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'message', name: 'message' },
            { data: 'status', name: 'status', orderable: true, searchable: true },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        pageLength: 10,
        order: [[0, 'desc']],
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

// Mark all as read
document.getElementById('markAllReadPageBtn')?.addEventListener('click', function() {
    fetch('{{ route("notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        $('#notificationsTable').DataTable().ajax.reload();
    }).catch(error => console.error('Error:', error));
});

// Mark single as read (event delegation for dynamically loaded rows)
$(document).on('click', '.mark-read-btn', function() {
    const id = $(this).data('id');
    fetch('{{ route("notifications.mark-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({id: id})
    }).then(() => {
        $('#notificationsTable').DataTable().ajax.reload();
    }).catch(error => console.error('Error:', error));
});
</script>
@endpush