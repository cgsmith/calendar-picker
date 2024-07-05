@extends('layouts/app')

@section('content')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
        <script>
            var end = Date.now() + 1000;
            var colors = ['#4a8fc4', '#FFA500', '#585858'];

            (function frame() {
                confetti({
                    particleCount: 2,
                    angle: 60,
                    spread: 55,
                    origin: {x: 0},
                    colors: colors,
                });
                confetti({
                    particleCount: 2,
                    angle: 120,
                    spread: 55,
                    origin: {x: 1},
                    colors: colors
                });
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        </script>
    @endpush

    <div class="dark:bg-gray-900 px-6 py-24 sm:py-32 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-4xl font-bold tracking-tight dark:text-white sm:text-6xl">{{__('Appointment Booked!')}}</h2>
            <p class="mt-6 text-lg leading-8 dark:text-gray-300">{{ __('Thank you! We look forward to meeting with you. We will send you an email with the appointment details. Please contact us if you have any questions.') }}</p>
        </div>
    </div>

@endsection
