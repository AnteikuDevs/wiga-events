@props([
    'id',
    'title',
    'withForm' => false,
    'submitText' => 'Simpan',
    'size' => '',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $size? 'modal-' . $size : '' }}">
        <div class="modal-content">
        @if ($withForm)
            <form method="POST">
        @endif
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <x-button type="button" class="btn-close" dismiss-modal></x-button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        @if ($withForm)
            </form>
        @endif
        </div>
    </div>
</div>
