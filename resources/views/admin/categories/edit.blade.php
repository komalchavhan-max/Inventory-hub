@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Category: {{ $category->name }}</h5>
                    <p class="text-muted mb-0">Update category details</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="PATCH">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Icon (Bootstrap Icon Class)</label>
                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                   value="{{ old('icon', $category->icon) }}" placeholder="bi-laptop, bi-display, bi-printer">
                            <small class="text-muted">Current icon: @if($category->icon) <i class="{{ $category->icon }}"></i> {{ $category->icon }} @else None @endif</small>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:check-read-line-duotone"></iconify-icon> Update Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <iconify-icon icon="solar:close-circle-line-duotone"></iconify-icon> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection