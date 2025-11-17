<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased">
        <div class="bg-gray-100 min-h-screen flex justify-center items-center">
            <form action="{{ route('donations.store') }}" method="POST"
                class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-md">
                @csrf
                <input type="hidden" name="username" value="{{ $user->username }}">

                <div class="space-y-8">
                    <!-- Header -->
                    <div class="border-b border-gray-900/10 pb-8">
                        <div class="flex justify-between items-center gap-x-6">
                            <div>
                                <h2 class="text-base/7 font-semibold text-gray-900">
                                    Kamu akan mengirimkan dukungan pada {{ $user->username }}
                                </h2>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    Silahkan lengkapi data ini
                                </p>
                            </div>

                            @guest
                                <a href="{{ route('login') }}"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm
                                hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                                focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Log in
                                </a>
                            @endguest
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-full">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Nama</label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    value="{{ auth()->check() ? auth()->user()->name : old('name') }}"
                                    @if(auth()->check()) readonly @endif>
                            </div>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="sm:col-span-full">
                        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                                @if(auth()->check()) readonly @endif>
                        </div>
                    </div>
                    <!-- Nominal -->
                    <div class="sm:col-span-full">
                        <label for="amount" class="block text-sm/6 font-medium text-gray-900">Nominal</label>
                        <div class="mt-2">
                            <input type="number" name="amount" id="amount" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>
                    <!-- Pesan -->
                    <div class="sm:col-span-full">
                        <label for="message" class="block text-sm/6 font-medium text-gray-900">Pesan</label>
                        <div class="mt-2">
                            <textarea type="number" name="message" id="message" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                        </div>
                    </div>
                    <!-- Tombol Kirim -->
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white
                                shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2
                                focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Kirim
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
