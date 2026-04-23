/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        primary: {
          DEFAULT: '#F54900',
          dark: '#B08D4B',
          light: '#F5F0EB',
        },
        dark: '#3D3D3D',
        text: {
          DEFAULT: '#4A4A4A',
          muted: '#8B7E70',
        },
        border: '#E8E0D5',
        bg: '#EDE5DA',
        success: '#10B981',
        warning: '#FFC107',
        danger: '#EF4444',
      },
      borderRadius: {
        DEFAULT: '8px',
        lg: '12px',
        xl: '16px',
      },
    },
  },
  plugins: [],
}
