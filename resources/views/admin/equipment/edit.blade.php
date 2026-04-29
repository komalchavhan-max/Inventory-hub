@extends('layouts.admin')

@section('content')
<div class="form-container">
    <div class="form-card card">
        <div class="card-header">
            <h5 class="mb-0">✏️ Edit Equipment: {{ $equipment->name }}</h5>
        </div>
        <div class="card-body">
            @include('components.form.error-messages')
            
            <form action="{{ route('admin.equipment.update', $equipment->id) }}" method="POST" class="needs-validation" novalidate id="editEquipmentForm">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name" class="form-label">Equipment Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $equipment->name) }}" required minlength="3" maxlength="255">
                    <div class="invalid-feedback">Equipment name is required (minimum 3 characters)</div>
                </div>
   
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $equipment->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a category</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="condition" class="form-label">Condition <span class="text-danger">*</span></label>
                        <select id="condition" name="condition" class="form-select" required>
                            <option value="">Select Condition</option>
                            <option value="Good" {{ old('condition', $equipment->condition) == 'Good' ? 'selected' : '' }}>👍 Good</option>
                            <option value="Fair" {{ old('condition', $equipment->condition) == 'Fair' ? 'selected' : '' }}>👌 Fair</option>
                            <option value="Poor" {{ old('condition', $equipment->condition) == 'Poor' ? 'selected' : '' }}>⚠️ Poor</option>
                        </select>
                        <div class="invalid-feedback">Please select a condition</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="Available" {{ old('status', $equipment->status) == 'Available' ? 'selected' : '' }}>✅ Available</option>
                        <option value="Assigned" {{ old('status', $equipment->status) == 'Assigned' ? 'selected' : '' }}>👤 Assigned</option>
                        <option value="In-Repair" {{ old('status', $equipment->status) == 'In-Repair' ? 'selected' : '' }}>🔧 In Repair</option>
                        <option value="Archived" {{ old('status', $equipment->status) == 'Archived' ? 'selected' : '' }}>📦 Archived</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" data-maxlength="1000">{{ old('description', $equipment->description) }}</textarea>
                    <div class="char-counter">
                        <span id="descriptionCount">0</span>/1000 characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="specifications" class="form-label">Specifications</label>
                    <textarea id="specifications" name="specifications" class="form-control" rows="3" placeholder='Example: processor: Intel i7, ram: 16GB'>{{ old('specifications', $equipment->specifications) }}</textarea>
                    <div class="form-text">💡 Type normally like "processor: i7, ram: 16GB" - auto converts to JSON</div>
                    <div class="invalid-feedback">Invalid JSON format</div>
                </div>
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" id="purchase_date" name="purchase_date" class="form-control" 
                               value="{{ old('purchase_date', $equipment->purchase_date ? date('Y-m-d', strtotime($equipment->purchase_date)) : '') }}">
                        <div class="invalid-feedback">Purchase date cannot be in the future</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="warranty_until" class="form-label">Warranty Expiry Date</label>
                        <input type="date" id="warranty_until" name="warranty_until" class="form-control" 
                               value="{{ old('warranty_until', $equipment->warranty_until ? date('Y-m-d', strtotime($equipment->warranty_until)) : '') }}">
                        <div class="form-text">⏰ Must be after purchase date</div>
                        <div class="invalid-feedback">Warranty expiry must be after purchase date</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="assigned_to" class="form-label">Assigned To</label>
                    <select id="assigned_to" name="assigned_to" class="form-select">
                        <option value="">Not Assigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $equipment->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">💾 Update Equipment</button>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Character counter for description
    $('#description').on('input', function() {
        var length = $(this).val().length;
        $('#descriptionCount').text(length);
        if (length > 1000) {
            $('#descriptionCount').addClass('text-danger');
        } else {
            $('#descriptionCount').removeClass('text-danger');
        }
    });
    
    // Initialize character count
    $('#descriptionCount').text($('#description').val().length);
    
    // Set max date for purchase date using JavaScript
    var today = new Date().toISOString().split('T')[0];
    $('#purchase_date').attr('max', today);
    
    // Date validation
    $('input[name="purchase_date"], input[name="warranty_until"]').on('change', function() {
        var purchaseDate = $('input[name="purchase_date"]').val();
        var warrantyDate = $('input[name="warranty_until"]').val();
        if (purchaseDate && warrantyDate && warrantyDate <= purchaseDate) {
            $('input[name="warranty_until"]').addClass('is-invalid');
        } else {
            $('input[name="warranty_until"]').removeClass('is-invalid');
        }
    });
});
</script>
@endpush