@props(['name', 'label', 'type' => 'text', 'required' => false, 'placeholder' => null, 'value' => null, 'help' => null, 'max' => null, 'min' => null, 'step' => null])

<div class="form-group">
    <label class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           class="form-control" 
           value="{{ old($name, $value) }}" 
           @if($required) required @endif
           @if($placeholder) placeholder="{{ $placeholder }}" @endif
           @if($max) max="{{ $max }}" @endif
           @if($min) min="{{ $min }}" @endif
           @if($step) step="{{ $step }}" @endif>
    
    {{-- Only show invalid feedback if field is required --}}
    @if($required)
        <div class="invalid-feedback">{{ $label }} is required</div>
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>