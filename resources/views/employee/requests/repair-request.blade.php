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
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="mb-0">🔧 Report Broken Equipment</h5>
                    <p class="text-muted mb-0">Fill this form to report equipment that needs repair</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.repair.store') }}" method="POST">
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
                                 
                        <!-- Photos Available -->
                        <div class="mb-3">
                            <label class="form-label">Do you have photos of the issue?</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input type="radio" name="photos_available" value="1" class="form-check-input">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="photos_available" value="0" class="form-check-input" checked>
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning">
                                Submit Repair Request
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