@push('styles')
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { margin-bottom: 16px; color: var(--ih-text-muted); font-size: 0.88rem; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--ih-border);
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.875rem;
        background: #fff;
        color: var(--ih-text);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .dataTables_wrapper .dataTables_filter input {
        min-width: 220px;
        padding-left: 12px;
    }
    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--ih-primary);
        box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
    }
    .dataTables_wrapper .dataTables_info {
        color: var(--ih-text-muted);
        font-size: 0.85rem;
        padding-top: 14px;
    }
    .dataTables_wrapper .pagination { margin-top: 14px; gap: 4px; }
    .dataTables_wrapper .page-link {
        border: 1px solid var(--ih-border);
        border-radius: 8px !important;
        color: var(--ih-text-muted);
        font-size: 0.85rem;
        padding: 6px 12px;
    }
    .dataTables_wrapper .page-item.active .page-link {
        background: var(--ih-primary);
        border-color: var(--ih-primary);
        color: #fff;
    }
    .dataTables_wrapper .page-link:hover {
        background: var(--ih-primary-light);
        color: var(--ih-primary);
        border-color: #c7d2fe;
    }
    #repairRequestsTable td:last-child { text-align: right; }
    
    /* Count chips styling */
    .count-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .tint-warning {
        background: #fef3c7;
        color: #d97706;
    }
    .tint-info {
        background: #e0f2fe;
        color: #0284c7;
    }
    .tint-success {
        background: #d1fae5;
        color: #059669;
    }
    .tint-danger {
        background: #fee2e2;
        color: #dc2626;
    }
    .count-value {
        font-weight: 700;
        min-width: 20px;
        text-align: center;
    }
    
    /* Issue description truncate */
    .issue-description {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Urgency badge styling */
    .urgency-critical {
        background: #dc2626;
        color: #fff;
    }
    .urgency-high {
        background: #f59e0b;
        color: #fff;
    }
    .urgency-medium {
        background: #3b82f6;
        color: #fff;
    }
    .urgency-low {
        background: #10b981;
        color: #fff;
    }
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
            search: "",
            searchPlaceholder: "Search repair requests...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_–_END_ of _TOTAL_",
            infoEmpty: "No entries",
            infoFiltered: "(filtered from _MAX_)",
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
        }
    });

    function showRejectModal(id, employeeName, equipmentName) {
        var $m = $('#rejectModalTemplate').children().clone();
        $m.find('.employee-name').val(employeeName);
        $m.find('.equipment-name').val(equipmentName);
        $m.find('form').attr('action', '/admin/requests/repair/' + id + '/reject');
        $m.modal('show');
    }
});
</script>
@endpush