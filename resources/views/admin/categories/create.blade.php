@extends('layouts.admin')

@section('content')
<div class="form-container">
    <div class="form-card card">
        <div class="card-header">
            <h5 class="mb-0">📁 Add New Category</h5>
        </div>
        <div class="card-body">
            @include('components.form.error-messages')
            
            <form action="{{ route('admin.categories.store') }}" method="POST" class="needs-validation" novalidate id="categoryForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required minlength="2" maxlength="100">
                    <div class="invalid-feedback">Category name is required (minimum 2 characters)</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                    <div class="form-text">Leave empty to auto-generate from name</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Icon</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="bi-laptop">
                    <div class="form-text">Bootstrap icon class (e.g., bi-laptop, bi-display)</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" data-maxlength="500">{{ old('description') }}</textarea>
                    <div class="char-counter">
                        <span id="descriptionCount">0</span>/500 characters
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">💾 Save Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection