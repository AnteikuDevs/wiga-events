@props(['items'])

<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
    @foreach ($items as $item)
        <li class="breadcrumb-item text-white opacity-75">
            @if (!empty($item['url']))
                <a href="{{ $item['url'] }}" class="text-white text-hover-primary">
                    {{ $item['name'] }}
                </a>
            @else
                {{ $item['name'] }}
            @endif
        </li>

        @if (!$loop->last)
            <li class="breadcrumb-item">
                <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
            </li>
        @endif
    @endforeach
</ul>
