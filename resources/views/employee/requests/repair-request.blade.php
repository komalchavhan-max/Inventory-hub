@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Report Repair Issue</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🔧 Report Broken Equipment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.repair.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Equipment Selection -->
                        <div class="mb-3">
                            <label class="form-label">Equipment to Repair <span class="text-danger">*</span></label>
                            <select name="equipment_id" class="form-select" required>
                                <option value="">-- Select Equipment --</option>
                                @foreach($myEquipment as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} - {{ $item->serial_number }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Issue Description -->
                        <div class="mb-3">
                            <label class="form-label">Describe the Issue <span class="text-danger">*</span></label>
                            <textarea name="issue_description" class="form-control" rows="4" required
                                placeholder="Example: Screen is flickering, keyboard keys not working, battery not charging..."></textarea>
                        </div>
                        
                        <!-- Urgency Level -->
                        <div class="mb-3">
                            <label class="form-label">How urgent is this?</label>
                            <select name="urgency" class="form-select">
                                <option value="Critical"> Critical - Work completely stopped</option>
                                <option value="High"> High - Major issue, affecting work</option>
                                <option value="Medium"> Medium - Issue exists but workable</option>
                                <option value="Low"> Low - Minor issue, can wait</option>
                            </select>
                        </div>
                        <div class="mt-4">
                             <button type="submit" class="btn btn-primary">
                                Submit Reapir Request
                            </button>
                            <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection