@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Return Equipment</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Equipment Return Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.return.store') }}" method="POST">
                        @csrf
                        
                        <!-- Equipment Selection -->
                        <div class="mb-3">
                            <label class="form-label">Equipment to Return <span class="text-danger">*</span></label>
                            <select name="equipment_id" class="form-select" required>
                                <option value="">-- Select Equipment --</option>
                                @foreach($myEquipment as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} - {{ $item->serial_number }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Return Reason -->
                        <div class="mb-3">
                            <label class="form-label">Reason for Return <span class="text-danger">*</span></label>
                            <select name="return_reason" class="form-select" required>
                                <option value="Leaving Company">📤 Leaving Company</option>
                                <option value="Exchange">🔄 Exchange for better equipment</option>
                                <option value="Broken">🔧 Equipment is broken</option>
                                <option value="Upgrade">⬆️ Upgrade to newer model</option>
                                <option value="Other">❓ Other</option>
                            </select>
                        </div>
                        
                        <!-- Equipment Condition -->
                        <div class="mb-3">
                            <label class="form-label">Current Equipment Condition <span class="text-danger">*</span></label>
                            <textarea name="equipment_condition" class="form-control" rows="3" required
                                placeholder="Describe any scratches, dents, missing parts, or issues..."></textarea>
                        </div>
                        
                        <!-- Missing Parts -->
                        <div class="mb-3">
                            <label class="form-label">Missing Parts or Accessories</label>
                            <textarea name="missing_parts" class="form-control" rows="2"
                                placeholder="Example: Charger missing, mouse not returned, power cord lost..."></textarea>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-info">
                                Submit Return Request
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