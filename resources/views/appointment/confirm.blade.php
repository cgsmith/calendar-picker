@extends('layouts/app')

@section('content')
    <div class="mt-16">
        <form id="confirmForm" method="POST" action="/confirm">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-white/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-white">{{$title}}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-400">{{ __('We need a little more information to schedule your appointment. All fields are required.') }}</p>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="name"
                                   class="block text-sm font-medium leading-6 text-white">{{__('Name')}}</label>
                            <div class="mt-2">
                                <div
                                    class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                    <input type="text" name="name" id="name" autocomplete="name" required
                                           class="flex-1 border-0 bg-transparent py-1.5 pl-1 text-white focus:ring-0 sm:text-sm sm:leading-6"
                                           placeholder="{{__('John Doe')}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label for="email"
                                   class="block text-sm font-medium leading-6 text-white">{{__('Email')}}</label>
                            <div class="mt-2">
                                <div
                                    class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                    <input type="email" name="email" id="email" autocomplete="email" required
                                           class="flex-1 border-0 bg-transparent py-1.5 pl-1 text-white focus:ring-0 sm:text-sm sm:leading-6"
                                           placeholder="{{__('john@example.com')}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label for="phone"
                                   class="block text-sm font-medium leading-6 text-white">{{__('Phone')}}</label>
                            <div class="mt-2">
                                <div
                                    class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                    <input type="text" name="phone" id="phone" autocomplete="phone" required
                                           class="flex-1 border-0 bg-transparent py-1.5 pl-1 text-white focus:ring-0 sm:text-sm sm:leading-6"">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {!! \App\Services\FormService::render($questions) !!}

                <input type="hidden" name="service_id" value="{{$service->id}}">
                <input type="hidden" name="user_id" value="{{$userid}}">
                <input type="hidden" name="start" value="{{$start}}">
                <input type="hidden" name="end" value="{{$end}}">


                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{__('Book Appointment')}}</button>
                </div>
            </div>
        </form>

    </div>

@endsection
