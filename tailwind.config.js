import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import preset from "./vendor/filament/filament/tailwind.config.preset";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream//*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views//*.blade.php",
        "./app/Filament//*.php",
        "./resources/views/filament//*.blade.php",
        "./vendor/filament//*.blade.php",
        "./resources//*.blade.php",
        "./vendor/andrewdwallo/filament-selectify/resources/views//*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
    darkMode: "false",
};