<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'UiTM College Housing') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100">
    
    <!-- Admin-Style Navigation Bar -->
    <nav class="bg-white border-b border-slate-200">
        <!-- max-w-7xl perfectly matches the wide admin layout -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" class="block h-9 w-auto fill-current text-slate-800">
                            <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.34 80.075C305.29 80.005 305.2 79.985 305.14 79.915C305.02 79.775 304.93 79.615 304.78 79.495C304.72 79.445 304.62 79.435 304.56 79.395C304.38 79.255 304.19 79.135 303.99 79.035C303.95 79.015 303.92 78.965 303.88 78.945L163.88 2.945C161.43 -0.984999 154.57 -0.984999 152.12 2.945L12.125 78.945C12.085 78.965 12.055 79.015 12.015 79.035C11.815 79.135 11.625 79.255 11.445 79.395C11.385 79.435 11.285 79.445 11.225 79.495C11.075 79.615 10.985 79.775 10.865 79.915C10.805 79.985 10.715 80.005 10.665 80.075C10.515 80.285 10.445 80.525 10.355 80.755C10.315 80.885 10.235 80.995 10.205 81.125C10.125 81.455 10.055 81.775 10.055 82.115V234.115C10.055 236.425 11.435 238.485 13.565 239.385L153.565 298.385C154.915 298.955 156.455 298.955 157.805 298.385L297.805 239.385C299.935 238.485 301.315 236.425 301.315 234.115V82.115C301.315 81.775 301.245 81.455 301.165 81.125H305.8ZM158 281L35.615 229.415V98.115L158 164.555V281ZM163.635 152.065L41.345 85.645L158 22.255L274.655 85.645L152.365 152.065H163.635ZM280.385 229.415L158 281V164.555L280.385 98.115V229.415Z"/>
                        </svg>
                    </div>

                    <!-- Student Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex text-sm">
                        <!-- Active links use border-blue-600 and text-slate-900 like the admin page -->
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-600 font-semibold text-slate-900' : 'border-transparent font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300' }} transition duration-150 ease-in-out">
                            Dashboard
                        </a>
                        <a href="{{ route('application.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('application.*') ? 'border-blue-600 font-semibold text-slate-900' : 'border-transparent font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300' }} transition duration-150 ease-in-out">
                            My application
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('profile.*') ? 'border-blue-600 font-semibold text-slate-900' : 'border-transparent font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300' }} transition duration-150 ease-in-out">
                            Account
                        </a>
                    </div>
                </div>

                <!-- User Dropdown area -->
                <div class="hidden sm:flex sm:items-center sm:ml-6 text-sm">
                    <div class="inline-flex items-center px-3 py-2 leading-4 font-medium text-slate-500 hover:text-slate-700 transition ease-in-out duration-150 cursor-pointer">
                        <div>{{ auth()->user()->name }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="ml-4 flex items-center">
                        @csrf
                        <button class="text-slate-500 hover:text-slate-900 font-medium transition duration-150 ease-in-out">
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Clean White Page Header -->
    @isset($header)
        <header class="bg-white shadow-[0_1px_2px_rgba(0,0,0,0.03)] border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-lg font-medium text-slate-900">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
        
        @if (session('status'))
            <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 shadow-sm">
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{ $slot }}
        
    </main>
</body>
</html>