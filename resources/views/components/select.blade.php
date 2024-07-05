@include('components.head', ['question' => $question])
    <div class="mt-2">
        <select id="{{$question->id}}"
                name="questions[{{$question->id}}]"
                autocomplete="off"
                {{ ($question->required) ? 'required' : '' }}
                class="block w-full rounded-md border-0 py-1.5 dark:text-white shadow-sm ring-1 dark:bg-slate-800 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
            <option></option>
            @foreach($question->type_meta as $meta)
                <option value="{{$meta}}">{{$meta}}</option>
            @endforeach
            </select>
    </div>
</div>
