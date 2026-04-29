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
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.equipment.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select Equipment <span class="text-danger">*</span></label>
                            <select name="equipment_id" class="form-control" required>
                                <option value="">-- Select Equipment --</option>
                                @foreach($availableEquipment as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select equipment</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-control">
                                <option value="Urgent">Urgent</option>
                                <option value="Normal">Normal</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Reason for Request <span class="text-danger">*</span></label>
                            <textarea name="request_reason" class="form-control" rows="4" required minlength="10"></textarea>
                            <div class="invalid-feedback">Please provide a reason (minimum 10 characters)</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection