@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Category Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td>{{ $category->slug }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $category->description ?? 'No description' }}</td>
                        </tr>
                        <tr>
                            <th>Icon</th>
                            <td>
                                @if($category->icon)
                                    <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                                @else
                                    No icon
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Equipment Count</th>
                            <td>
                                <span class="badge bg-primary">{{ $category->equipment_count ?? 0 }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $category->created_at ? $category->created_at->format('d-m-Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $category->updated_at ? $category->updated_at->format('d-m-Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Category
                </a>
            </div>
        </div>
    </div>
</div>
@endsection