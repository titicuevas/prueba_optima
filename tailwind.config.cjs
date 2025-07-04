/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  safelist: [
    'bg-blue-600',
    'text-white',
    'p-8',
    'rounded-xl',
    'text-2xl',
    'shadow-lg',
    'px-6',
    'py-3',
    'font-bold',
    'hover:bg-blue-700',
    'transition',
    'duration-150',
    'ease-in-out',
    'bg-green-500',
    'bg-purple-500',
    'bg-yellow-400',
    'bg-gray-200',
    'text-gray-700',
    'rounded-full',
    'shadow-md',
    'hover:bg-green-600',
    'hover:bg-purple-600',
    'hover:bg-gray-300',
    'hover:scale-105',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-blue-400',
    'focus:ring-green-400',
    'focus:ring-purple-400',
    'text-xs',
    'text-base',
    'text-lg',
    'text-3xl',
    'text-blue-700',
    'bg-blue-50',
    'bg-white',
    'border',
    'border-blue-100',
    'border-blue-800',
    'bg-gradient-to-r',
    'from-blue-200',
    'to-purple-200',
    'from-blue-500',
    'to-purple-500',
    'from-blue-900',
    'to-purple-900',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

