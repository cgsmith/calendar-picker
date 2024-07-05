@include('components.head', ['question' => $question])
    <div class="mt-2">
        <div class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
            @if(isset($type) && $type === 'textarea')
                <textarea id="{{$question->id}}"
                          name="questions[{{$question->id}}]"
                          {!! ($question->required) ? 'required' : '' !!}
                          rows="3"
                          class="block w-full rounded-md border-0 dark:bg-slate-800 py-1.5 dark:text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"></textarea>
            @else
                <input type="text"
                       name="questions[{{$question->id}}]"
                       id="{{$question->id}}"
                       {!! ($question->required) ? 'required' : '' !!}
                       autocomplete="off"
                       class="flex-1 rounded-md dark:bg-slate-800 py-1.5 pl-1 dark:text-white focus:ring-0 sm:text-sm sm:leading-6">
            @endif
        </div>
    </div>
</div>
