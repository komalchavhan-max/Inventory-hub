@extends('layouts.admin')

@section('title', 'Return Requests')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Return Requests</h5>
            <div>
                <span class="badge bg-warning p-2">Pending: <span id="pendingCount">0</span></span>
                <span class="badge bg-info p-2">Approved: <span id="approvedCount">0</span></span>
                <span class="badge bg-success p-2">Completed: <span id="completedCount">0</span></span>
                <span class="badge bg-danger p-2">Rejected: <span id="rejectedCount">0</span></span>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="returnRequestsTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Equipment</th>
                            <th>Return Reason</th>
                            <th>Condition</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Admin Message</th>
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

<!-- Reject Modal Template -->
<div id="rejectModalTemplate" style="display: none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Reject Return Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Employee:</strong> <span class="employee-name"></span></p>
                        <p><strong>Equipment:</strong> <span class="equipment-name"></span></p>
                        <div class="mb-3">
                            <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="rejection_message" class="form-control" rows="4" required 
                                placeholder="Please explain why this return request is being rejected..."></textarea>
                            <small class="text-muted">This message will be sent to the employee.</small>
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

<!-- Message View Modal Template -->
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
    var table = $('#returnRequestsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/admin/requests/return-data") }}',
            type: 'GET',
            dataSrc: function(json) {
                console.log('Return data received:', json);
                return json.data;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'employee_name', name: 'employee_name' },
            { data: 'equipment_name', name: 'equipment_name' },
            { data: 'return_reason', name: 'return_reason' },
            { data: 'equipment_condition', name: 'equipment_condition' },
            { data: 'return_date', name: 'return_date' },
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
            var pending = 0, approved = 0, completed = 0, rejected = 0;
            
            api.rows().data().each(function(row) {
                if (row.status && row.status.includes('Pending')) pending++;
                if (row.status && row.status.includes('Approved')) approved++;
                if (row.status && row.status.includes('Completed')) completed++;
                if (row.status && row.status.includes('Rejected')) rejected++;
            });
            
            $('#pendingCount').text(pending);
            $('#approvedCount').text(approved);
            $('#completedCount').text(completed);
            $('#rejectedCount').text(rejected);
            
            $('.reject-btn').off('click').on('click', function() {
                var id = $(this).data('id');
                var employeeName = $(this).data('employee');
                var equipmentName = $(this).data('equipment');
                showRejectModal(id, employeeName, equipmentName);
            });
            
            $('.view-message-btn').off('click').on('click', function() {
                var message = $(this).data('message');
                showMessageModal(message);
            });
        }
    });
    
    function showRejectModal(id, employeeName, equipmentName) {
        var $template = $('#rejectModalTemplate').children().clone();
        $template.find('.employee-name').text(employeeName);
        $template.find('.equipment-name').text(equipmentName);
        $template.find('form').attr('action', '/admin/requests/return/' + id + '/reject');
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