const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Frank Ruhl Libre', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                'discount-blue': '#489CD8',
                'cta-orange': '#F17105',
                'dark-blue': '#1D3675',
                'navy-blue': '#114273',
                'money-green': '#159301',
                'discount-gray': '#F7F9FB',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@vueform/slider/tailwind'),
    ],
};
