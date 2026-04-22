@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Notifications</h5>
            <button class="btn btn-sm btn-primary" id="markAllReadPageBtn">Mark all as read</button>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ !$notification->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">{{ $notification->message }}</p>
                                <small class="text-muted">{{ $notification->created_at ? $notification->created_at->diffForHumans() : 'Just now' }}</small>
                            </div>
                            <span class="badge bg-{{ $notification->status == 'Approved' ? 'success' : ($notification->status == 'Rejected' ? 'danger' : 'info') }}">
                                {{ $notification->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No notifications found</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('markAllReadPageBtn')?.addEventListener('click', function() {
    fetch('{{ route("notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => window.location.reload());
});
</script>
@endpush