@extends('layouts/app')

@section('content')


<div class="mt-16">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        @foreach ($services as $service)

        <a href="/appointment/service/{{$service->id}}" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">{{ $service->name }}</h2>
                {!! $service->description !!}
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"  class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 01-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 11-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 016.336-4.486l-3.276 3.276a3.004 3.004 0 002.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008z" />
            </svg>

        </a>
        @endforeach

    </div>
</div>

{{ time() }}
@endsection
