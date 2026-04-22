@extends('layouts.admin')

@section('title', 'Repair Requests')

@section('content')
<div class="card mb-0">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
        <h5 class="mb-0">Repair Requests</h5>
        <div class="d-flex flex-wrap gap-2">
            <span class="count-chip tint-warning">Pending <span class="count-value" id="pendingCount">0</span></span>
            <span class="count-chip tint-info">Approved <span class="count-value" id="approvedCount">0</span></span>
            <span class="count-chip tint-success">Completed <span class="count-value" id="completedCount">0</span></span>
            <span class="count-chip tint-danger">Rejected <span class="count-value" id="rejectedCount">0</span></span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle" id="repairRequestsTable" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Equipment</th>
                        <th>Issue</th>
                        <th>Urgency</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Admin Message</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div id="rejectModalTemplate" style="display:none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-x-circle text-danger me-2"></i>Reject Repair Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            <strong class="text-dark">Employee:</strong> <span class="employee-name"></span><br>
                            <strong class="text-dark">Equipment:</strong> <span class="equipment-name"></span>
                        </p>
                        <div>
                            <label class="form-label">Reason for rejection <span class="text-danger">*</span></label>
                            <textarea name="rejection_message" class="form-control" rows="4" required
                                      placeholder="Please explain why this repair request is being rejected..."></textarea>
                            <small class="text-muted">This message will be sent to the employee.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Send &amp; Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="messageModalTemplate" style="display:none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-chat-dots text-primary me-2"></i>Admin Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="small text-muted mb-1">Reason for rejection</div>
                    <div class="alert alert-danger message-content mb-0"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { margin-bottom: 16px; color: var(--ih-text-muted); font-size: 0.88rem; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--ih-border); border-radius: 8px; padding: 6px 10px;
        font-size: 0.875rem; background: #fff; color: var(--ih-text);
    }
    .dataTables_wrapper .dataTables_filter input { min-width: 220px; }
    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none; border-color: var(--ih-primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
    }
    .dataTables_wrapper .dataTables_info { color: var(--ih-text-muted); font-size: 0.85rem; padding-top: 14px; }
    .dataTables_wrapper .pagination { margin-top: 14px; gap: 4px; }
    .dataTables_wrapper .page-link {
        border: 1px solid var(--ih-border); border-radius: 8px !important;
        color: var(--ih-text-muted); font-size: 0.85rem; padding: 6px 12px;
    }
    .dataTables_wrapper .page-item.active .page-link {
        background: var(--ih-primary); border-color: var(--ih-primary); color: #fff;
    }
    .dataTables_wrapper .page-link:hover {
        background: var(--ih-primary-light); color: var(--ih-primary); border-color: #c7d2fe;
    }
    #repairRequestsTable td:last-child { text-align: right; }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    var table = $('#repairRequestsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: '{{ url("/admin/requests/repair-data") }}', type: 'GET' },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'employee_name', name: 'employee_name' },
            { data: 'equipment_name', name: 'equipment_name' },
            { data: 'issue_description', name: 'issue_description' },
            { data: 'urgency', name: 'urgency' },
            { data: 'request_date', name: 'request_date' },
            { data: 'status', name: 'status' },
            { data: 'admin_message_display', name: 'admin_message_display', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
        ],
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "", searchPlaceholder: "Search requests...",
            lengthMenu: "Show _MENU_", info: "Showing _START_–_END_ of _TOTAL_",
            infoEmpty: "No entries", infoFiltered: "(filtered from _MAX_)",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        },
        drawCallback: function () {
            var api = this.api();
            var pending = 0, approved = 0, completed = 0, rejected = 0;
            api.rows().data().each(function (row) {
                if (row.status && row.status.indexOf('Pending') !== -1) pending++;
                if (row.status && row.status.indexOf('Approved') !== -1) approved++;
                if (row.status && row.status.indexOf('Completed') !== -1) completed++;
                if (row.status && row.status.indexOf('Rejected') !== -1) rejected++;
            });
            $('#pendingCount').text(pending);
            $('#approvedCount').text(approved);
            $('#completedCount').text(completed);
            $('#rejectedCount').text(rejected);

            $('.reject-btn').off('click').on('click', function () {
                showRejectModal($(this).data('id'), $(this).data('employee'), $(this).data('equipment'));
            });
            $('.view-message-btn').off('click').on('click', function () { showMessageModal($(this).data('message')); });
        }
    });

    function showRejectModal(id, employeeName, equipmentName) {
        var $m = $('#rejectModalTemplate').children().clone();
        $m.find('.employee-name').text(employeeName);
        $m.find('.equipment-name').text(equipmentName);
        $m.find('form').attr('action', '/admin/requests/repair/' + id + '/reject');
        $m.modal('show');
    }
    function showMessageModal(message) {
        var $m = $('#messageModalTemplate').children().clone();
        $m.find('.message-content').text(message);
        $m.modal('show');
    }
});
</script>
@endpush
