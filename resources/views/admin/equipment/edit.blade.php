@extends('layouts.admin')

@section('title', 'Edit Equipment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Equipment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.equipment.update', $equipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Equipment Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $equipment->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $equipment->serial_number) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="Laptop" {{ $equipment->category == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Desktop" {{ $equipment->category == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="Monitor" {{ $equipment->category == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="Keyboard" {{ $equipment->category == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                <option value="Mouse" {{ $equipment->category == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                <option value="Printer" {{ $equipment->category == 'Printer' ? 'selected' : '' }}>Printer</option>
                                <option value="Chair" {{ $equipment->category == 'Chair' ? 'selected' : '' }}>Chair</option>
                                <option value="Desk" {{ $equipment->category == 'Desk' ? 'selected' : '' }}>Desk</option>
                                <option value="Other" {{ $equipment->category == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="Available" {{ $equipment->status == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Assigned" {{ $equipment->status == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="In-Repair" {{ $equipment->status == 'In-Repair' ? 'selected' : '' }}>In Repair</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Condition</label>
                            <select name="condition" class="form-control">
                                <option value="New" {{ $equipment->condition == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Good" {{ $equipment->condition == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Fair" {{ $equipment->condition == 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option value="Poor" {{ $equipment->condition == 'Poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $equipment->description) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Specifications</label>
                            <textarea name="specifications" class="form-control" rows="2">{{ old('specifications', $equipment->specifications) }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Purchase Date</label>
                                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $equipment->purchase_date) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Warranty Until</label>
                                    <input type="date" name="warranty_until" class="form-control" value="{{ old('warranty_until', $equipment->warranty_until) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Assigned To</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">Not Assigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $equipment->assigned_to == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Equipment</button>
                        <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection