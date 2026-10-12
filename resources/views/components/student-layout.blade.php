<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <nav class="bg-brand text-black">
        <div class="max-w-4xl mx-auto px-6 h-14 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <span class="font-semibold">UiTM Jasin College Housing</span>
                <div class="flex gap-5 text-sm">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-white font-medium' : 'text-black/70 hover:text-white' }}">Home</a>
                    <a href="{{ route('application.create') }}" class="{{ request()->routeIs('application.*') ? 'text-black font-medium' : 'text-white/70 hover:text-white' }}">My application</a>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'text-black font-medium' : 'text-white/70 hover:text-white' }}">Account</a>
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-white/70">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="underline text-white/80 hover:text-white">Log out</button>
                </form>
            </div>
        </div>
    </nav>

    @isset($header)
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-6 py-5">
                <h1 class="text-xl font-semibold">{{ $header }}</h1>
            </div>
        </header>
    @endisset

    <main class="max-w-4xl mx-auto px-6 py-8 space-y-4">
        @if (session('status'))
            <div class="rounded-md bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-md bg-red-50 text-red-700 text-sm px-4 py-3">{{ $errors->first() }}</div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>