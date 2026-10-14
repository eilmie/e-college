<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900">
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex flex-col justify-between bg-brand text-white p-12">
            <div>
                <p class="text-sm text-white/70">UiTM Jasin</p>
                <p class="text-xl font-semibold">College Housing System</p>
            </div>
            <div>
                <h2 class="text-3xl font-semibold leading-snug max-w-md">Apply for a college room and follow the result in one place.</h2>
                <p class="mt-4 text-white/70 max-w-md">Students sign in with their UiTM student email. College staff sign in with their staff account.</p>
            </div>
            <p class="text-sm text-white/50">© {{ date('Y') }} UiTM Jasin College Housing</p>
        </div>

        <div class="flex items-center justify-center p-6 bg-slate-50">
            <div class="w-full max-w-md card card-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>