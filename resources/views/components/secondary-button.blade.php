<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary inline-flex justify-center items-center font-semibold']) }}>
    {{ $slot }}
</button>
