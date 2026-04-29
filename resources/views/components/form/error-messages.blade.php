@if($errors->any())
    <div class="form-alert form-alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="form-alert form-alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="form-alert form-alert-danger">
        ❌ {{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="form-alert form-alert-warning">
        ⚠️ {{ session('warning') }}
    </div>
@endif

@if(session('info'))
    <div class="form-alert form-alert-info">
        ℹ️ {{ session('info') }}
    </div>
@endif