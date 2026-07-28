@props([
    'disabled' => false,
    'error' => false
])

@php
    $baseClasses = 'w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border rounded-xl focus:ring-2 focus:outline-none transition-colors duration-200 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500';
    
    if ($error) {
        $classes = $baseClasses . ' border-red-300 focus:border-red-500 focus:ring-red-200 dark:border-red-600 dark:focus:ring-red-900/50';
    } else {
        $classes = $baseClasses . ' border-gray-200 dark:border-gray-700 focus:border-brand-light focus:ring-brand-light/30 dark:focus:ring-brand-light/20';
    }
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
