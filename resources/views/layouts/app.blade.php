<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    @vite('resources/css/app.css')
    @stack('head-scripts')
    <style>

        .bg-dots-darker {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E")
        }

        @media (prefers-color-scheme: dark) {
            .dark\:bg-dots-lighter {
                background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E")
            }
        }

    </style>
</head>
<body class="antialiased">
<div
    class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/home') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
            @else
                <a href="{{ route('login') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                    in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        <div class="flex justify-center">
            <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 131.964 14.26">
                    <defs>
                        <style>.a04 {
                                fill: #fff
                            }

                            .b04 {
                                fill: url(#a)
                            }</style>
                        <linearGradient id="a" y1="0.5" x2="1" y2="0.5" gradientUnits="objectBoundingBox">
                            <stop offset="0" stop-color="#fba44e"></stop>
                            <stop offset="1" stop-color="#d7180c"></stop>
                        </linearGradient>
                    </defs>
                    <g id="m7-logo-svg" transform="translate(65.982 7.13)">
                        <g transform="translate(-65.982 -7.13)">
                            <g transform="translate(0 0.686)">
                                <path class="a04"
                                      d="M6.956,9.537q.133.316.262.638.129-.33.267-.649t.293-.616l3.931-7.359a.99.99,0,0,1,.151-.221.468.468,0,0,1,.174-.12.768.768,0,0,1,.219-.045c.079-.006.174-.009.285-.009H14.4v13.28H12.226V5.859c0-.159.005-.333.014-.523s.023-.383.042-.577l-4.017,7.53a1.051,1.051,0,0,1-.36.4.928.928,0,0,1-.515.144H7.053a.926.926,0,0,1-.514-.144,1.047,1.047,0,0,1-.36-.4L2.112,4.729q.037.3.05.592c.009.193.014.373.014.537v8.578H0V1.157H1.864a2.7,2.7,0,0,1,.288.009.768.768,0,0,1,.216.045.5.5,0,0,1,.179.12.908.908,0,0,1,.156.221L6.668,8.926Q6.823,9.22,6.956,9.537Z"
                                      transform="translate(0 -1.009)"></path>
                                <path class="a04"
                                      d="M41.131,7.739a7.358,7.358,0,0,1-.487,2.7,6.406,6.406,0,0,1-1.37,2.154,6.191,6.191,0,0,1-2.121,1.419,7.664,7.664,0,0,1-5.5,0A6.223,6.223,0,0,1,29.527,12.6a6.362,6.362,0,0,1-1.37-2.154,7.344,7.344,0,0,1-.487-2.7,7.347,7.347,0,0,1,.487-2.7,6.366,6.366,0,0,1,1.37-2.153,6.234,6.234,0,0,1,2.129-1.419,7.186,7.186,0,0,1,2.753-.51,7.1,7.1,0,0,1,2.746.515,6.293,6.293,0,0,1,2.121,1.419,6.363,6.363,0,0,1,1.37,2.149A7.36,7.36,0,0,1,41.131,7.739Zm-2.535,0a6.182,6.182,0,0,0-.288-1.961A4.148,4.148,0,0,0,37.476,4.3a3.59,3.59,0,0,0-1.318-.928,4.941,4.941,0,0,0-3.5,0,3.616,3.616,0,0,0-1.327.928,4.176,4.176,0,0,0-.84,1.474,6.1,6.1,0,0,0-.293,1.961A6.1,6.1,0,0,0,30.487,9.7a4.129,4.129,0,0,0,.84,1.469,3.629,3.629,0,0,0,1.327.923,4.93,4.93,0,0,0,3.5,0,3.6,3.6,0,0,0,1.318-.923,4.1,4.1,0,0,0,.83-1.469A6.186,6.186,0,0,0,38.6,7.739Z"
                                      transform="translate(-7.725 -0.952)"></path>
                                <path class="a04"
                                      d="M59.25,12.444a3.253,3.253,0,0,0,1.281-.239,2.667,2.667,0,0,0,.955-.67,2.937,2.937,0,0,0,.6-1.047,4.325,4.325,0,0,0,.209-1.377V1.157h2.471V9.11a6.127,6.127,0,0,1-.381,2.19,4.938,4.938,0,0,1-1.1,1.736,5,5,0,0,1-1.736,1.139,6.659,6.659,0,0,1-4.592,0A4.868,4.868,0,0,1,54.133,11.3a6.127,6.127,0,0,1-.381-2.19V1.157h2.47V9.1a4.305,4.305,0,0,0,.207,1.377,3.018,3.018,0,0,0,.592,1.052,2.6,2.6,0,0,0,.951.675,3.251,3.251,0,0,0,1.277.239Z"
                                      transform="translate(-15.007 -1.009)"></path>
                                <path class="a04"
                                      d="M79.115,1.171a.63.63,0,0,1,.2.056.662.662,0,0,1,.17.119,1.884,1.884,0,0,1,.183.207l6.971,8.881q-.037-.322-.05-.629t-.014-.577V1.157h2.177v13.28H87.471a1.132,1.132,0,0,1-.487-.092,1.081,1.081,0,0,1-.376-.33L79.665,5.17q.028.293.042.583c.009.193.014.37.014.528v8.155H77.544V1.157h1.3A2.311,2.311,0,0,1,79.115,1.171Z"
                                      transform="translate(-21.649 -1.009)"></path>
                                <path class="a04" d="M110.469,1.157v2.03h-3.995v11.25H104V3.187H99.99V1.157Z"
                                      transform="translate(-27.915 -1.009)"></path>
                                <path class="a04"
                                      d="M130.315,1.157V2.14a2.177,2.177,0,0,1-.1.711,4.236,4.236,0,0,1-.189.455l-5.023,10.359a1.562,1.562,0,0,1-.44.546,1.212,1.212,0,0,1-.771.226h-1.643l5.142-10.15a5.265,5.265,0,0,1,.716-1.075h-6.355a.534.534,0,0,1-.524-.524V1.157Z"
                                      transform="translate(-33.817 -1.009)"></path>
                            </g>
                            <path class="b04"
                                  d="M169.608.835,160.53,13.779a.786.786,0,0,1-.643.334h-.51L151.841,3.367l-.6.86,6.933,9.886h-5.489a.786.786,0,0,1-.643-.334l-7.3-10.412-1.7,2.421-4.479,6.394h1.225l3.7-5.271,1.179,1.683-3.636,5.186a.786.786,0,0,1-.643.334H134.84L144.737,0l8.541,12.182h1.176l-5.578-7.955L151.841,0l8,11.4,6.055-8.633h-7.437a.614.614,0,0,1-.614-.614V.835Z"
                                  transform="translate(-37.645)"></path>
                        </g>
                    </g>
                </svg>
            </a>
        </div>

        @yield('content')

    </div>
@stack('scripts')
</body>
</html>
