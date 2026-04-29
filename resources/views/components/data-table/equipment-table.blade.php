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
   
    #equipmentTable td:last-child { text-align: right; }
    
   
    #statusFilter {
        border: 1px solid var(--ih-border);
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.875rem;
        background: #fff;
        color: var(--ih-text);
        cursor: pointer;
    }
    #statusFilter:focus {
        outline: none;
        border-color: var(--ih-primary);
        box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
    }
</style>
@endpush

@push('scripts')
<script>
$(function () {
   
    const urlParams = new URLSearchParams(window.location.search);
    const statusFromUrl = urlParams.get('status');
    
    if (statusFromUrl) {
        $('#statusFilter').val(statusFromUrl);
    }
    
    var table = $('#equipmentTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { 
            url: '{{ url("/admin/equipment-data") }}', 
            type: 'GET',
            data: function(d) {
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { data: 'id', name: 'equipment.id', orderable: true },
            { data: 'name', name: 'equipment.name', orderable: true },
            { data: 'category_name', name: 'categories.name', orderable: true },
            { data: 'status', name: 'equipment.status', orderable: true },
            { data: 'condition', name: 'equipment.condition', orderable: true },
            { data: 'assigned_to_name', name: 'users.name', orderable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
        ],
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search equipment...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_–_END_ of _TOTAL_",
            infoEmpty: "No entries to show",
            infoFiltered: "(filtered from _MAX_)",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        }
    });
    
    // Reload table when status filter changes
    $('#statusFilter').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endpush