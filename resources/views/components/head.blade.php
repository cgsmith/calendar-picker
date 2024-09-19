@php use App\Enums\QuestionType; @endphp
@once
    @push('scripts')
        <script>
            function toggleChildren(childId) {
                const divs = document.querySelectorAll(`div#child${childId}`);

                // Loop through each div and toggle
                if (divs.length > 0) {
                    divs.forEach(div => {
                        div.classList.toggle('hidden');
                    });
                }
            }
        </script>
    @endpush
@endonce

<div
    class="question mt-10 col-span-full border-b border-black/20 dark:border-white/10 pb-12
    {{ $question->parent_service_question_id ? 'hidden' : '' }}
    {{ ($question->type === QuestionType::toggle) ? 'flex items-center justify-between' : '' }}"
    @if ($question->parent_service_question_id)
        id="child{{$question->parent_service_question_id}}"
    @endif
    >
    <label for="{{$question->id}}" class="block text-sm font-medium leading-6 dark:text-white">{{$question->question}}
        {!! ($question->required) ? '' : '<small>'.__('(optional)').'</small>' !!}
    </label>
    <p class="mt-1 text-sm leading-6 dark:text-gray-400">{{$question->hint}}</p>
    <!-- Closing div occurs in different template -->
