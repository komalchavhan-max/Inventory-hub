@extends('layouts.admin')

@section('content')
<div class="form-container">
    <div class="form-card card">
        <div class="card-header">
            <h5 class="mb-0">➕ Add New Equipment</h5>
        </div>
        <div class="card-body">
            @include('components.form.error-messages')
            
            <form action="{{ route('admin.equipment.store') }}" method="POST" class="needs-validation" novalidate id="equipmentForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Equipment Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required minlength="3" maxlength="255">
                    <div class="invalid-feedback">Equipment name is required (minimum 3 characters)</div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a category</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-select" required>
                            <option value="">Select Condition</option>
                            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>👍 Good</option>
                            <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>👌 Fair</option>
                            <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>⚠️ Poor</option>
                        </select>
                        <div class="invalid-feedback">Please select a condition</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" data-maxlength="1000">{{ old('description') }}</textarea>
                    <div class="char-counter">
                        <span id="descriptionCount">0</span>/1000 characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Specifications</label>
                    <textarea name="specifications" class="form-control" rows="3" placeholder='Example: processor: Intel i7, ram: 16GB'>{{ old('specifications') }}</textarea>
                    <div class="form-text">💡 Type normally like "processor: i7, ram: 16GB" - auto converts to JSON</div>
                </div>
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}" max="{{ date('Y-m-d') }}" id="purchase_date">>
                        <div class="form-text">📅 Cannot select future dates</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Warranty Expiry Date</label>
                        <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry') }}" id="warranty_expiry">
                        <div class="form-text">⏰ Must be after purchase date</div>
                    </div>
                </div>   
                         
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">💾 Save Equipment</button>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection