/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',   // Blade templates
    './resources/**/*.js',          // JavaScript files (React components or plain JS)
    './resources/**/*.vue',         // Vue components
    './resources/**/*.jsx',         // React JSX files
    './resources/**/*.ts',          // TypeScript files
    './resources/**/*.tsx',         // React TSX files
    './resources/**/*.html',        // Static HTML files
    './resources/**/*.svelte',      // Svelte components
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
