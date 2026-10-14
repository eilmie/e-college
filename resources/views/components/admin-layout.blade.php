<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <aside class="w-60 bg-brand text-white flex-shrink-0 flex flex-col">
            <div class="px-6 py-5 border-b border-white/10">
                <p class="text-xs text-white/60">UiTM Jasin</p>
                <p class="font-semibold leading-tight">College Housing</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                <a href="{{ route('admin.applications.index') }}"
                   class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.applications.*') ? 'bg-white/10 font-medium' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                    Applications
                </a>
                <a href="{{ route('admin.rooms.index') }}"
                   class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.rooms.*') || request()->routeIs('admin.allocations.*') ? 'bg-white/10 font-medium' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                    Rooms
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-white/10 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center text-sm font-medium">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm truncate">{{ auth()->user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-xs text-white/60 hover:text-white underline">Log out</button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 min-w-0 flex flex-col">
            @isset($header)
                <header class="bg-white border-b border-slate-200 px-8 py-5">
                    <h1 class="text-lg font-semibold">{{ $header }}</h1>
                    @isset($subtitle)
                        <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
                    @endisset
                </header>
            @endisset

            <main class="flex-1 p-8">
                <div class="max-w-6xl space-y-6">
                    @if (session('status'))
                        <div class="alert-success">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert-error">{{ $errors->first() }}</div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>