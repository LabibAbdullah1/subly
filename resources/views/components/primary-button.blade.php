<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary w-full inline-flex justify-center items-center shadow-[0_4px_12px_rgba(255,255,255,0.08)] hover:shadow-[0_6px_16px_rgba(255,255,255,0.15)] font-semibold']) }}>
    {{ $slot }}
</button>
