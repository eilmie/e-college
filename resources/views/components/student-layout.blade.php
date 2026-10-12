<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'UiTM Jasin College Housing') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 h-14 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <span class="font-semibold text-black">UiTM Jasin College Housing</span>
                
                <!-- Removed 'hidden' so links always show -->
                <div class="flex gap-5 text-sm">
                    <a href="{{ route('dashboard') }}" class="transition-colors {{ request()->routeIs('dashboard') ? 'text-black font-semibold' : 'text-slate-500 hover:text-black' }}">
                        Home
                    </a>
                    <a href="{{ route('application.create') }}" class="transition-colors {{ request()->routeIs('application.*') ? 'text-black font-semibold' : 'text-slate-500 hover:text-black' }}">
                        My application
                    </a>
                    <a href="{{ route('profile.edit') }}" class="transition-colors {{ request()->routeIs('profile.*') ? 'text-black font-semibold' : 'text-slate-500 hover:text-black' }}">
                        Account
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm">
                <!-- User Initial Circle -->
                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-slate-500 hover:text-red-600 font-medium transition-colors">
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </nav>

    @isset($header)
        <header class="bg-white shadow-sm border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-6 py-5">
                <h1 class="text-xl font-semibold text-black">{{ $header }}</h1>
            </div>
        </header>
    @endisset

    <main class="max-w-4xl mx-auto px-6 py-8 space-y-4">
        
        @if (session('status'))
            <div class="flex items-center gap-3 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 shadow-sm">
                <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-center gap-3 rounded-md bg-red-50 border border-red-200 px-4 py-3 shadow-sm">
                <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
            </div>
        @endif

        {{ $slot }}
        
    </main>
</body>
</html>