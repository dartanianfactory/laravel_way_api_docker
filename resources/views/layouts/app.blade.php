<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Task Manager</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: '#0a0a0a',
                        'neon-blue': '#00f3ff',
                        'neon-purple': '#b967ff', 
                        'neon-pink': '#ff2a6d',
                        'neon-green': '#39ff14',
                        'neon-cyan': '#00ffcc',
                        'dark-gray': '#1a1a1a',
                        'darker-gray': '#121212',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
</head>
<body class="min-h-screen bg-dark text-white">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-neon-cyan via-neon-purple to-neon-pink bg-clip-text text-transparent mb-2">
                Task Manager
            </h1>
            <p class="text-neon-cyan opacity-80">Testing skill for job</p>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>
