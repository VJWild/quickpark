import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                // Montserrat para todo el texto general (Body)
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
                // Poppins exclusivamente para Títulos y resaltados
                poppins: ['Poppins', 'sans-serif'],
            },
            colors: {
                // Tailwind ya tiene blue e indigo excelentes, pero podemos forzar el tema
                qp: {
                    blue: '#2563eb',   // blue-600
                    indigo: '#4f46e5', // indigo-600
                    light: '#f8fafc',  // slate-50 (fondo base)
                }
            }
        },
    },
    plugins: [forms],
};
