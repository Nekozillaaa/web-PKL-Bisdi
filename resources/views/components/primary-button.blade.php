<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-brand inline-flex items-center gap-2']) }}>
    {{ $slot }}
</button>
