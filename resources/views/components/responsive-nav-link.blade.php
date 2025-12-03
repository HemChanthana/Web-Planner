@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-sky-500 bg-sky-100 text-sky-700 font-medium'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-gray-700 hover:text-sky-700 hover:bg-slate-100 hover:border-slate-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
