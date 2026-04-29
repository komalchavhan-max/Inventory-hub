@props(['name', 'label', 'required' => false, 'placeholder' => null, 'value' => null, 'help' => null, 'rows' => 3, 'maxlength' => null])

<div class="form-group">
    <label class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea name="{{ $name }}" 
              class="form-control" 
              rows="{{ $rows }}"
              @if($required) required @endif
              @if($placeholder) placeholder="{{ $placeholder }}" @endif
              @if($maxlength) data-maxlength="{{ $maxlength }}" @endif>{{ old($name, $value) }}</textarea>
    
    {{-- Only show invalid feedback if field is required --}}
    @if($required)
        <div class="invalid-feedback">{{ $label }} is required</div>
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @if($maxlength)
        <div class="char-counter">
            📝 <span id="{{ $name }}Count">0</span>/{{ $maxlength }} characters
        </div>
    @endif
</div>