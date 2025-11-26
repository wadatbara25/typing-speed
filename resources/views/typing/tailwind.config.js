/** @type {import('tailwindcss').Config} */
export default {
  // Scan content files
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/**/*.ts",
  ],

  theme: {
    // Container setup
    container: {
      center: true,
      padding: "1rem",
    },

    extend: {
      // Fonts
      fontFamily: {
        sans: ["Tajawal", "Cairo", "ui-sans-serif", "system-ui"],
        mono: ["Cascadia Code", "Consolas", "monospace"],
      },

      // Custom colors
      colors: {
        primary: {
          50: "#eef2ff",
          100: "#e0e7ff",
          200: "#c7d2fe",
          300: "#a5b4fc",
          400: "#818cf8",
          500: "#6366f1",
          600: "#4f46e5",
          700: "#4338ca",
          800: "#3730a3",
          900: "#312e81",
        },
        success: {
          100: "#dcfce7",
          200: "#bbf7d0",
          300: "#86efac",
          400: "#4ade80",
          500: "#22c55e",
        },
        danger: {
          100: "#fee2e2",
          200: "#fecaca",
          300: "#fca5a5",
          400: "#f87171",
          500: "#ef4444",
        },
        warning: {
          100: "#fef9c3",
          200: "#fef08a",
          300: "#fde047",
          400: "#facc15",
          500: "#eab308",
        },
      },
    },
  },

  // Allow dynamic classes
  safelist: [
    "bg-green-100", "text-green-800",
    "bg-red-100", "text-red-800",
    "bg-yellow-100",
    "text-gray-700",
    "text-indigo-700", "bg-indigo-500", "bg-indigo-400",
    "bg-red-500", "bg-green-500", "bg-blue-500",
    "text-indigo-500", "text-blue-600", "text-orange-500", "text-violet-600",
  ],

  // Plugins
  plugins: [
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
    require("tailwindcss-rtl"), // RTL support
  ],
};
