@php use App\Enums\QuestionType; @endphp
<div
    class="mt-10 col-span-full border-b border-black/20 dark:border-white/10 pb-12 {{ ($question->type === QuestionType::toggle) ? 'flex items-center justify-between' : '' }}">
    <label for="{{$question->id}}" class="block text-sm font-medium leading-6 dark:text-white">{{$question->question}}
        {!! ($question->required) ? '' : '<small>'.__('(optional)').'</small>' !!}
    </label>
    <p class="mt-1 text-sm leading-6 dark:text-gray-400">{{$question->hint}}</p>
    <!-- Closing div occurs in different template -->
