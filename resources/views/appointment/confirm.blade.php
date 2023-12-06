@extends('layouts/app')

@section('content')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
@endpush
<div class="mt-16">

        <div class="flex">
            <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex focus:outline focus:outline-2 focus:outline-red-500">
                <div>
                    <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                    <form method="POST" action="/confirm">
                        @csrf

                    </form>
                    Name: <input type="text">
                    @foreach($questions as $question)
                        <p class="dark:text-white ">{{$question->question}}</p>
                        <input type="text" />
                    @endforeach
                    <button>Confirm</button>
                </div>

            </div>

        </div>

</div>

@endsection
