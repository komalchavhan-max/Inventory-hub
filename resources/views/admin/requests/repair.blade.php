@extends('layouts.admin')

@section('content')
<div class="card mb-0">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
        <h5 class="mb-0">🔧 Repair Requests</h5>
        <div class="d-flex flex-wrap gap-2">
            <span class="count-chip tint-warning">⏳ Pending <span class="count-value" id="pendingCount">0</span></span>
            <span class="count-chip tint-info">👀 Approved <span class="count-value" id="approvedCount">0</span></span>
            <span class="count-chip tint-success">✅ Completed <span class="count-value" id="completedCount">0</span></span>
            <span class="count-chip tint-danger">❌ Rejected <span class="count-value" id="rejectedCount">0</span></span>
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

{{-- Reject Modal --}}
<div id="rejectModalTemplate" style="display:none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">❌ Reject Repair Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Employee</label>
                            <input type="text" class="form-control employee-name" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Equipment</label>
                            <input type="text" class="form-control equipment-name" readonly disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reason for rejection <span class="text-danger">*</span></label>
                            <textarea name="rejection_message" class="form-control" rows="4" required placeholder="Please explain why this repair request is being rejected..."></textarea>
                            <div class="form-text">This message will be sent to the employee</div>
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

{{-- Message Modal --}}
<div id="messageModalTemplate" style="display:none;">
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">💬 Admin Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Reason for rejection</label>
                        <div class="alert alert-danger message-content mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('components.data-table.repair-requests-table')