@include('components.head', ['question' => $question])
        <div class="mt-6 space-y-6">
            @foreach($question->type_meta as $meta)
                <div class="relative flex gap-x-3">
                    <div class="flex h-6 items-center">
                        <input id="{{$question->id}}-{{$meta }}"
                               value="{{$meta}}"
                               name="questions[{{$question->id}}]"
                               type="{{$type}}"
                               {{ ($question->required) ? 'required' : '' }}
                               class="h-4 w-4 rounded dark:bg-slate-800 text-indigo-600 focus:ring-indigo-600 focus:ring-offset-gray-900">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="{{ $question->id }}-{{ $meta }}" class="font-medium dark:text-white">{{$meta}}</label>
                    </div>
                </div>
            @endforeach
        </div>
</div>
