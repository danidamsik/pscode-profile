<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script>
            (() => {
                let theme = null;

                try {
                    theme = localStorage.getItem('pscode-theme');
                } catch (error) {
                    theme = null;
                }

                const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;

                document.documentElement.classList.toggle('dark', theme ? theme === 'dark' : prefersDark);
            })();
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="bg-white font-sans antialiased dark:bg-tokyo-bg">
        @inertia
    </body>
</html>
