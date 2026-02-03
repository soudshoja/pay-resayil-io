/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Space Grotesk', 'Cairo', 'sans-serif'],
      },
      colors: {
        dark: {
          900: '#0a0a0f',
          800: '#12121a',
          700: '#1a1a24',
          600: '#24242f',
          500: '#2f2f3d',
        },
        accent: {
          purple: '#a855f7',
          pink: '#ec4899',
          cyan: '#22d3ee',
        }
      }
    },
  },
  plugins: [],
}
