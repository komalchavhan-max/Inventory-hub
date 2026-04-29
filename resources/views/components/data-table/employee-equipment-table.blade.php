@push('scripts')
<script>
$(function () {
    $('#equipmentTable').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 10,
        order: [[3, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search equipment requests...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_–_END_ of _TOTAL_",
            infoEmpty: "No entries",
            infoFiltered: "(filtered from _MAX_)",
            paginate: { first: "«", last: "»", next: "›", previous: "‹" }
        }
    });
});
</script>
@endpush