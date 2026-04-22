@extends('layouts.admin')

@section('title', 'Equipment Requests')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Equipment Requests</h5>
            <div>
                <span class="badge bg-warning p-2">Pending: <span id="pendingCount">0</span></span>
                <span class="badge bg-success p-2">Fulfilled: <span id="fulfilledCount">0</span></span>
                <span class="badge bg-danger p-2">Rejected: <span id="rejectedCount">0</span></span>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="equipmentRequestsTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Equipment</th>
                            <th>Priority</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Admin Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="rejectModalTemplate" style="display: none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Reject Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Employee:</strong> <span class="employee-name"></span></p>
                        <p><strong>Equipment:</strong> <span class="equipment-name"></span></p>
                        <div class="mb-3">
                            <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="rejection_message" class="form-control" rows="4" required 
                                placeholder="Please explain why this request is being rejected..."></textarea>
                            <small class="text-muted">This message will be sent to the employee and visible to both.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Send & Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="messageModalTemplate" style="display: none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Admin Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Reason for Rejection:</strong></p>
                    <div class="alert alert-danger message-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#equipmentRequestsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/admin/requests/equipment-data") }}',
            type: 'GET',
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'employee_name', name: 'employee_name' },
            { data: 'equipment_name', name: 'equipment_name' },
            { data: 'priority', name: 'priority' },
            { data: 'request_date', name: 'request_date' },
            { data: 'status', name: 'status' },
            { data: 'admin_message_display', name: 'admin_message_display', orderable: false, searchable: false },
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
        },
        drawCallback: function() {
            var api = this.api();
            var pending = 0, fulfilled = 0, rejected = 0;
            
            api.rows().data().each(function(row) {
                if (row.status && row.status.includes('Pending')) pending++;
                if (row.status && row.status.includes('Fulfilled')) fulfilled++;
                if (row.status && row.status.includes('Rejected')) rejected++;
            });
            
            $('#pendingCount').text(pending);
            $('#fulfilledCount').text(fulfilled);
            $('#rejectedCount').text(rejected);
            
            $('.view-message-btn').off('click').on('click', function() {
                var message = $(this).data('message');
                showMessageModal(message);
            });
            
            $('.reject-btn').off('click').on('click', function() {
                var id = $(this).data('id');
                var employeeName = $(this).data('employee');
                var equipmentName = $(this).data('equipment');
                showRejectModal(id, employeeName, equipmentName);
            });
        }
    });
    
    function showRejectModal(id, employeeName, equipmentName) {
        var $template = $('#rejectModalTemplate').children().clone();
        $template.find('.employee-name').text(employeeName);
        $template.find('.equipment-name').text(equipmentName);
        $template.find('form').attr('action', '/admin/requests/equipment/' + id + '/reject');
        $template.modal('show');
    }
    
    function showMessageModal(message) {
        var $template = $('#messageModalTemplate').children().clone();
        $template.find('.message-content').text(message);
        $template.modal('show');
    }
});
</script>
@endpush