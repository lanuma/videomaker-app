@extends('layouts.appnew')

@section('content')

<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto w-full">
        <div class="relative px-1 py-10 bg-white shadow-lg sm:rounded-3xl sm:px-6">
            <div class="max-w-md mx-auto">
                <div class="flex justify-center">
                    <h1 class="text-gray-700 font-bold text-3xl">Youtube</h1>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">

                        <div>
                            <div class="flex w-full mt-4 justify-center">
                                @auth
                                    @livewire('youtube')
                                @endauth

                                @guest
                                    <a href="{{ route('youtube.login') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Login</a>
                                @endguest

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- @isset ($message)
        <div class="mt-8 relative px-1 py-10 bg-white shadow-lg sm:rounded-3xl sm:px-6">
            <div class="bg-white px-1 py-1 sm:rounded-3xl sm:px-6">
                <div class="bg-gray-200 text-xs">
                    @php
                    print_r($message);
                    @endphp
                </div>
            </div>
        </div>
        @endisset --}}

    </div>
</div>

@endsection
