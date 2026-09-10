<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-nf-blue border border-transparent rounded-full font-heading font-bold text-sm text-white hover:bg-nf-blue-dark focus:bg-nf-blue-dark active:bg-nf-blue-dark focus:outline-none focus:ring-2 focus:ring-nf-blue focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
