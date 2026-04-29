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
        min-width: 200px;
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
    /* Actions column - compact, right-aligned */
    #maintenanceLogsTable td:last-child { text-align: right; }
    
    /* Cost styling */
    .cost-amount {
        font-weight: 600;
        color: var(--ih-text);
    }
    
    /* Issue description truncate */
    .issue-description {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    var table = $('#maintenanceLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { 
            url: '{{ url("/admin/maintenance-logs-data") }}', 
            type: 'GET',
        },
        columns: [
            { data: 'id', name: 'maintenance_logs.id', orderable: true },
            { data: 'equipment_name', name: 'equipment.name', orderable: true },
            { data: 'issue_description', name: 'maintenance_logs.issue_description', orderable: true },
            { data: 'status_display', name: 'repair_requests.status', orderable: true },
            { data: 'repair_date', name: 'maintenance_logs.repair_date', orderable: true },
            { data: 'created_at', name: 'maintenance_logs.created_at', orderable: true },
        ],
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search maintenance logs...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_–_END_ of _TOTAL_",
            infoEmpty: "No entries to show",
            infoFiltered: "(filtered from _MAX_)",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        }
    });
});
</script>
@endpush