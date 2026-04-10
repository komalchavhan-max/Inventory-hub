@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Request Equipment</h4>
            </div>
        </div>
    </div>

    <!-- Request Form Card -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Equipment Request</h5>
                    <p class="text-muted mb-0">Fill out the form below to request equipment</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.equipment.store') }}" method="POST">
                        @csrf
                        
                        <!-- Equipment Selection -->
                        <div class="mb-3">
                            <label class="form-label">Select Equipment <span class="text-danger">*</span></label>
                            <select name="equipment_id" class="form-select" required>
                                <option value="">-- Choose Equipment --</option>
                                @foreach($availableEquipment as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} - {{ $item->serial_number }}
                                    ({{ $item->category }})
                                </option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <!-- Priority Selection -->
                        <div class="mb-3">
                            <label class="form-label">Priority Level</label>
                            <select name="priority" class="form-select">
                                <option value="Urgent">🔴 Urgent - Need immediately</option>
                                <option value="Normal">🟡 Normal - Within a week</option>
                                <option value="Low">🟢 Low - No rush</option>
                            </select>
                        </div>
                        
                        <!-- Request Reason -->
                        <div class="mb-3">
                            <label class="form-label">Reason for Request <span class="text-danger">*</span></label>
                            <textarea name="request_reason" class="form-control" rows="4" 
                                placeholder="Explain why you need this equipment..." required></textarea>
                            @error('request_reason')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <!-- Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-send-plane-fill"></i> Submit Request
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