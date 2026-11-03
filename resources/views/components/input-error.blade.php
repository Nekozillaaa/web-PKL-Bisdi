@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs text-red-400 space-y-1 mt-1.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
