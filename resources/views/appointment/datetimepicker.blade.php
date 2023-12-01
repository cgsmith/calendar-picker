@extends('layouts/app')

@section('content')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
@endpush
<div class="mt-16">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div>
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ $service->description }}</p>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
            </svg>
        </div>
        <div class="flex">
            <div>
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Pretend I am a calendar</h2>
                @foreach($availableTimes as $userid => $userTimes)
                    @foreach($userTimes as $time)
                    <p><a
                            class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed"
                            href="/appointment/service/{{$service->id}}/{{$userid}}/{{$time->date->format('U')}}{{ $confirm }}">
                            {{ $time->date->format($format) }}
                        </a
                        ></p>
                    @endforeach
                @endforeach
            </div>

        </div>

    </div>
</div>

@endsection
