@props([
    'title', 
    'action', 
    'method' => 'POST', 
    'submitText' => 'Save', 
    'cancelRoute' => null,
    'id' => null,
    'enctype' => null
])

<div class="form-container">
    <div class="form-card card">
        <div class="card-header">
            <h5 class="mb-0">{{ $title }}</h5>
        </div>
        <div class="card-body">
            @include('components.form.error-messages')
            
            <form {{ $id ? "id=$id" : '' }} 
                  action="{{ $action }}" 
                  method="{{ $method === 'GET' ? 'GET' : 'POST' }}" 
                  class="needs-validation" 
                  novalidate
                  {{ $enctype ? "enctype=$enctype" : '' }}>
                @csrf
                @if($method !== 'GET' && $method !== 'POST')
                    @method($method)
                @endif
                
                {{ $slot }}
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        💾 {{ $submitText }}
                    </button>
                    @if($cancelRoute)
                        <a href="{{ $cancelRoute }}" class="btn btn-secondary">
                            ❌ Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>