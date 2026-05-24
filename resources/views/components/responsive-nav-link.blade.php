@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2.5 border-l-2 border-primary-500 text-start text-sm font-semibold text-white bg-neutral-900/60 focus:outline-none transition duration-200 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2.5 border-l-2 border-transparent text-start text-sm font-medium text-neutral-400 hover:text-neutral-100 hover:bg-neutral-900/30 hover:border-neutral-850 focus:outline-none focus:text-neutral-200 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
