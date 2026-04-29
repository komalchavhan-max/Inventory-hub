@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Exchange Equipment</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Exchange Request Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.exchange.store') }}" method="POST" class="needs-validation" novalidate id="exchangeForm">
                        @csrf
                        
                        <!-- Current Equipment (Old) -->
                        <div class="mb-3">
                            <label class="form-label">Equipment to Exchange <span class="text-danger">*</span></label>
                            <select name="old_equipment_id" class="form-select" required>
                                <option value="">-- Select Your Equipment --</option>
                                @foreach($myEquipment as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Requested Equipment (New) -->
                        <div class="mb-3">
                            <label class="form-label">Requested Equipment <span class="text-danger">*</span></label>
                            <select name="requested_equipment_id" class="form-select" required>
                                <option value="">-- Select New Equipment --</option>
                                @foreach($availableEquipment as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} 
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Exchange Reason -->
                        <div class="mb-3">
                            <label class="form-label">Why do you need an exchange? <span class="text-danger">*</span></label>
                            <textarea name="exchange_reason" class="form-control" rows="3" required
                                placeholder="Example: Old laptop is slow, need better specifications for work..."></textarea>
                        </div>
                        
                        <!-- Current Equipment Condition -->
                        <div class="mb-3">
                            <label class="form-label">Current Equipment Condition <span class="text-danger">*</span></label>
                            <textarea name="old_equipment_condition" class="form-control" rows="2" required
                                placeholder="Describe scratches, dents, any issues..."></textarea>
                        </div>
                        
                        <!-- Damage Question (Conditional) -->
                        <div class="mb-3">
                            <label class="form-label">Does the equipment have any damage?</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input type="radio" name="has_damage" value="1" class="form-check-input" id="damage_yes">
                                    <label class="form-check-label" for="damage_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="has_damage" value="0" class="form-check-input" id="damage_no" checked>
                                    <label class="form-check-label" for="damage_no">No</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Damage Description (Shows only if Yes selected) -->
                        <div class="mb-3" id="damageField" style="display: none;">
                            <label class="form-label">Describe the damage</label>
                            <textarea name="damage_description" class="form-control" rows="2"
                                placeholder="Example: Screen has crack, keyboard keys missing..."></textarea>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Submit Exchange Request
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

<script>
// Show/hide damage description field
const damageYes = document.getElementById('damage_yes');
const damageNo = document.getElementById('damage_no');
const damageField = document.getElementById('damageField');

damageYes.addEventListener('change', function() {
    if(this.checked) {
        damageField.style.display = 'block';
    }
});

damageNo.addEventListener('change', function() {
    if(this.checked) {
        damageField.style.display = 'none';
    }
});
</script>
@endsection