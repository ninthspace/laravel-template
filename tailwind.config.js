module.exports = {
    purge: [
        './resources/views/**/*.blade.php',
        './resources/css/**/*.css',
        './vendor/ninthspace/floorshow/resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {}
    },
    variants: {},
    plugins: [
        require('@tailwindcss/custom-forms'),
        require('@tailwindcss/ui'),
    ]
};
