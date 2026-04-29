@props(['name', 'label', 'required' => false, 'options' => [], 'selected' => null, 'placeholder' => null, 'help' => null])

<div class="form-group">
    <label class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select name="{{ $name }}" class="form-select" @if($required) required @endif>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>
    
    {{-- Only show invalid feedback if field is required --}}
    @if($required)
        <div class="invalid-feedback">Please select {{ strtolower($label) }}</div>
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>