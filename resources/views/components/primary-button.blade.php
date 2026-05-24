<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full btn-primary py-2.5 inline-flex justify-center items-center shadow-[0_0_15px_rgba(94,106,210,0.25)] hover:shadow-[0_0_20px_rgba(94,106,210,0.4)] transition-all']) }}>
    {{ $slot }}
</button>
