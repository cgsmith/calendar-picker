@extends('layouts/app')

@section('content')

@push('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>
@endpush

<div class="mt-16">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                {!! $service->description !!}
            </div>

        </div>
        <div class="flex">
            <div>
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">What would you like to have the repair done?</h2>
                <p class="text-gray-500">This is when the workshop will repair the bike, we would like to have the bike the day before.</p>
                <input id="datepicker" type="hidden"/>
                <script>
                    const allowedDates = [
                        @foreach($availableTimes as $time)
                                '{{ $time->date->format('Y-m-d') }}',
                        @endforeach
                    ]
                    const picker = new easepick.create({
                        element: document.getElementById('datepicker'),
                        firstDay: 0,
                        inline: true,
                        calendars: 2,
                        lang: 'de',
                        css: [
                            'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                            '{{asset('css/calendar.css')}}',

                        ],
                        plugins: ['LockPlugin'],
                        LockPlugin: {
                            filter(date, picked) {
                                return !allowedDates.includes(date.format('YYYY-MM-DD'));
                            },
                        },
                    });
                    picker.on('select', (e) => {
                       const date = new Date(e.detail.date);
                       const redirectUrl = '{{ url("/appointment/service/{$service->id}/{$userid}") }}/' + date.getTime()/1000 + '/confirm';
                       window.location.replace(redirectUrl);
                    });
                </script>

        </div>

    </div>
</div>

@endsection
