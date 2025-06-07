@props([
    'type' => 'text',
    'label' => '',
    'value' => '',
    'required' => false,
    'name' => '',
    'class' => '',
    'autocomplete' => '',
])

@php
    // Ambil ID dari atribut, kalau tidak ada generate acak
    $attrId = $attributes->get('id');
    $inputId = $attrId ?: 'input_' . str_random(8);

    // Cek apakah ada placeholder dan simpan nilainya
    $placeholderText = $attributes->get('placeholder');

    // Buat attribute bag baru.
    // Mulai dengan semua atribut yang diberikan.
    $attrs = $attributes;

    // Tambahkan 'id' jika belum ada.
    if (!$attrId) {
        $attrs = $attrs->merge(['id' => $inputId]);
    }

    // Jika ada placeholder, HAPUS dari attribute bag yang akan di-render di input.
    // Teks placeholder akan kita tampilkan manual di bawah menggunakan tag <small>.
    if ($placeholderText) {
        $attrs = $attrs->except('placeholder');
    }
@endphp

@if($type == 'upload-image')

    <wiga-upload-image accept="image/*" name="{{ $name }}" title="{{ $label }}"></wiga-upload-image>

@elseif($type == 'textarea')

<div class="form-floating mb-4">
    <textarea name="{{ $name }}" placeholder="" class="form-control min-h-100px {{ $class }}"{{ $required ? ' required' : '' }}{{ $autocomplete ? " autocomplete=$autocomplete " : '' }} {{ $attrs }}>{{ old($name, $value) }}</textarea>
    <label for="{{ $inputId }}">{{ $label }}</label>
    {{-- Tampilkan placeholder sebagai teks bantuan jika ada --}}
    @if($placeholderText)
        <small class="text-muted d-block mt-1">{{ $placeholderText }}</small>
    @endif
    <div data-error="{{ $name }}"></div>
</div>

@elseif($type == 'checkbox')

<div class="mb-4">
    <div class="form-check form-check-custom form-check-solid">
        <input class="form-check-input" name="{{ $name }}" type="checkbox" id="{{ $inputId }}" value="{{ old($name, $value) }}" {{ $attrs }}/>
        <label class="form-check-label" for="{{ $inputId }}">
            {{ $label }}
        </label>
    </div>
    <div data-error="{{ $name }}"></div>
</div>

@elseif($type == 'select')

<div class="form-floating mb-4">
    <select name="{{ $name }}" class="form-select {{ $class }}"{{ $required ? ' required' : '' }}{{ $autocomplete ? " autocomplete=$autocomplete " : '' }} {{ $attrs }}>
        <option value="" disabled selected>{{ $label }}</option>
        {{ $slot }}
    </select>
    <label for="{{ $inputId }}">{{ $label }}</label>
    {{-- Tampilkan placeholder sebagai teks bantuan jika ada --}}
    @if($placeholderText)
        <small class="text-muted d-block mt-1">{{ $placeholderText }}</small>
    @endif
    <div data-error="{{ $name }}"></div>
</div>

@else

<div class="form-floating mb-4">
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" placeholder="" class="form-control {{ $class }}"{{ $required ? ' required' : '' }}{{ $autocomplete ? " autocomplete=$autocomplete " : '' }} {{ $attrs }}>
    <label for="{{ $inputId }}">{{ $label }}</label>
    {{-- Tampilkan placeholder sebagai teks bantuan jika ada --}}
    @if($placeholderText)
        <small class="text-muted d-block mt-1">{{ $placeholderText }}</small>
    @endif
    <div data-error="{{ $name }}"></div>
</div>

@endif