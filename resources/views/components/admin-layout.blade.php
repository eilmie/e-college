<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-brand text-white flex-shrink-0 flex flex-col">
            <div class="px-6 py-5 border-b border-white/10">
                <p class="text-sm text-white/60">UiTM Jasin</p>
                <p class="font-semibold text-lg leading-tight">College Housing</p>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('dashboard') ? 'bg-white/10 font-medium' : 'text-white/80 hover:bg-white/5' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.applications.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.applications.*') ? 'bg-white/10 font-medium' : 'text-white/80 hover:bg-white/5' }}">
                    Applications
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.rooms.*') ? 'bg-white/10 font-medium' : 'text-white/80 hover:bg-white/5' }}">
                    Rooms
                </a>
            </nav>
            <div class="px-6 py-4 border-t border-white/10 text-sm text-white/60">
                {{ auth()->user()->name }}
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button class="text-white/80 hover:text-white underline">Log out</button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            @isset($header)
                <header class="bg-white border-b border-slate-200 px-8 py-5">
                    <h1 class="text-xl font-semibold text-slate-900">{{ $header }}</h1>
                </header>
            @endisset
            <main class="flex-1 p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>