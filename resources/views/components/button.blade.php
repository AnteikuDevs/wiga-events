@props([
    'type' => 'button',           // button, submit, reset
    'color' => 'primary',         // Bootstrap color: primary, danger, success, etc.
    'size' => 'sm',                 // sm, lg, or leave empty
    'href' => null,               // if provided, render as <a>
    'block' => false,             // full-width button
    'indicator' => false,         // if true, show indicator label and spinner
])

@php

    $attr = collect($attributes->getAttributes());
    
    if ($attr->has('toggle-modal')) {
        $target = $attr->pull('toggle-modal');
        $attr->put('data-bs-toggle', 'modal');
        $attr->put('data-bs-target', "#{$target}");
    }

    if ($attr->has('dismiss-modal')) {
        $attr->pull('dismiss-modal');
        $attr->put('data-bs-dismiss', 'modal');
    }

    $classFromAttr = $attr->get('class', '');

    $isBtnCloseOnly = trim($classFromAttr) === 'btn-close';

    if ($isBtnCloseOnly) {
        $finalClass = 'btn-close';
        $attr = $attr->except('class');
    } else {
        $finalClass = 'btn btn-' . $color;
        if ($size) $finalClass .= ' btn-' . $size;
        if ($block) $finalClass .= ' w-100';
    }

    $attr = new \Illuminate\View\ComponentAttributeBag($attr->all());

@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attr->merge(['class' => $finalClass]) }}>
        @if ($indicator)
            <span class="indicator-label">{!! $slot !!}</span>
            <span class="indicator-progress">
                <span class="spinner-border spinner-border-sm align-middle"></span>
            </span>
        @else
            {!! $slot !!}
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attr->merge(['class' => $finalClass]) }}>
        @if ($indicator)
            <span class="indicator-label">{!! $slot !!}</span>
            <span class="indicator-progress">
                <span class="spinner-border spinner-border-sm align-middle"></span>
            </span>
        @else
            {!! $slot !!}
        @endif
    </button>
@endif
