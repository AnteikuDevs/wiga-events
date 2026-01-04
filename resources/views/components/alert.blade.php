@props([
    'type' => 'info',         // primary, secondary, success, danger, warning, info
    'dismissible' => true,    // true atau false
])

@php
    // Mapping warna kustom dan ikon FontAwesome (FA)
    $config = match($type) {
        'primary' => [
            'icon' => 'fas fa-star',
            'bg'   => '#eef2ff', 
            'text' => '#4338ca',
            'border' => '#6366f1'
        ],
        'secondary' => [
            'icon' => 'fas fa-layer-group',
            'bg'   => '#f8fafc',
            'text' => '#475569',
            'border' => '#94a3b8'
        ],
        'success' => [
            'icon' => 'fas fa-check-circle',
            'bg'   => '#e8f5e9',
            'text' => '#0f5132',
            'border' => '#00c853'
        ],
        'danger' => [
            'icon' => 'fas fa-exclamation-circle',
            'bg'   => '#ffebe6',
            'text' => '#842029',
            'border' => '#ff3d00'
        ],
        'warning' => [
            'icon' => 'fas fa-exclamation-triangle',
            'bg'   => '#fff8e1',
            'text' => '#664d03',
            'border' => '#ffa000'
        ],
        'info' => [
            'icon' => 'fas fa-info-circle',
            'bg'   => '#e0eaff',
            'text' => '#0061ff',
            'border' => '#0061ff'
        ],
        default => [
            'icon' => 'fas fa-info-circle',
            'bg'   => '#f8f9fa',
            'text' => '#1e293b',
            'border' => '#94a3b8'
        ],
    };

    $baseStyles = "
        display: flex; 
        align-items: center; 
        padding: 1.25rem 1.5rem; 
        background-color: {$config['bg']}; 
        color: {$config['text']}; 
        border: none;
        border-left: 5px solid {$config['border']}; 
        border-radius: 12px;
        gap: 10px;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    ";
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . ($dismissible ? 'alert-dismissible fade show' : ''), 'role' => 'alert', 'style' => $baseStyles]) }}>
    
    <div style="display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: {{ $config['border'] }};padding: .5rem">
        <i class="{{ $config['icon'] }} fa-1x"></i>
    </div>

    <div style="flex-grow: 1; font-size: 0.95rem; font-weight: 500; line-height: 1.5;">
        {!! $slot !!}
    </div>

    @if($dismissible)
        <button type="button" 
                class="btn-close-custom" 
                data-bs-dismiss="alert" 
                aria-label="Close"
                style="
                    background: none;
                    border: none;
                    color: #94a3b8;
                    font-size: 1.25rem;
                    cursor: pointer;
                    padding: 0;
                    margin-left: 10px;
                    transition: color 0.2s;
                    display: flex;
                    align-items: center;
                ">
            <i class="fas fa-times"></i>
        </button>
    @endif
</div>