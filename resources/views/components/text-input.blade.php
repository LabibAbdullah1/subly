@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input-field bg-gray-900 border-gray-700 text-gray-200 focus:border-primary-500 focus:ring-primary-500/50 rounded-lg shadow-inner py-2.5 px-3 transition-colors placeholder-gray-500']) }}>
