/** @type {import('tailwindcss').Config} */

module.exports = {
    content: ['./**/*.{html,js}'],
    theme: {
        extend: {
            fontFamily: {
                oswald: ['Oswald', 'sans-serif'],
                roboto: ['Roboto', 'sans-serif'],
            },
        },
        colors: {
            background: '#FFF9F9',
            mainRed: '#DF2828',
            lightRed: '#FFE8E4',
            white: '#FFFFFF',
            blue: '#5863F8',
            yellow: '#ECA72C',
            black: '#000000',
        },
        screens: {
            sm: '370px',
            xl: '1280px',
        },
        fontSize: {
            tiny: '0.6rem',
            sm: '0.8rem',
            base: '1rem',
            xl: '1.25rem',
            '2xl': '1.563rem',
            '3xl': '1.953rem',
            '4xl': '2.881rem',
        },
    },
    plugins: [],
}
