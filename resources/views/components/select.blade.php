@props(['disabled' => false, 'error' => false])

@php
    // We use appearance-none and a custom inline SVG chevron so the arrow doesn't look ugly/mepet.
    // pr-10 ensures the text doesn't overlap with the arrow.
    $baseClasses = 'w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:ring-2 focus:outline-none transition-colors duration-200 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 appearance-none bg-no-repeat bg-[url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e")] bg-[position:right_1rem_center] bg-[length:1.5em_1.5em] pr-10 cursor-pointer';
    
    if ($error) {
        $classes = $baseClasses . ' border-red-300 focus:border-red-500 focus:ring-red-200 dark:border-red-600 dark:focus:ring-red-900/50';
    } else {
        $classes = $baseClasses . ' border-gray-200 dark:border-gray-700 focus:border-brand-light focus:ring-brand-light/30 dark:focus:ring-brand-light/20';
    }
@endphp

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
    {{ $slot }}
</select>
