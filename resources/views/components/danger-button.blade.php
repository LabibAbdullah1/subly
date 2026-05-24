<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-danger inline-flex justify-center items-center font-semibold']) }}>
    {{ $slot }}
</button>
