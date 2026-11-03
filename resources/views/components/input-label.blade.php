@props(['value'])

<label {{ $attributes->merge(['class' => 'label-dark']) }}>
    {{ $value ?? $slot }}
</label>
