@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'help' => null,
])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}@if($required)<span class="text-danger ms-1">*</span>@endif</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @class(['form-control', 'is-invalid' => $errors->has($name)])
        @if($required) required @endif
    >
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


