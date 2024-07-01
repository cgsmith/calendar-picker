<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\QuestionType;
use App\Models\ServiceQuestion;
use Illuminate\Database\Eloquent\Collection;

class FormService
{
    public static function render(?Collection $questions)
    {
        if (count($questions) === 0) {
            return '';
        }

        $html = "<div>
                    <h2 class='text-base font-semibold leading-7 dark:text-white'>".__('A few questions')."</h2>
                    <p class='mt-1 text-sm leading-6 text-gray-700 dark:text-gray-400'>".__('These questions help us during your appointment.').'</p>';

        /** @var ServiceQuestion $question */
        foreach ($questions as $question) {
            $html .= match ($question->type) {
                QuestionType::text => self::text($question),
                QuestionType::textarea => self::text($question, 'textarea'),
                QuestionType::select => self::select($question),
                QuestionType::checkbox => self::checkbox($question),
                QuestionType::radio => self::checkbox($question, 'radio'),
                QuestionType::toggle => self::toggle($question),
            };
        }

        return $html.'</div>';
    }

    public static function text(ServiceQuestion $question, $type = 'text')
    {
        $html = '<div class="mt-10 col-span-full border-b border-black/20 dark:border-white/10 pb-12">
            <label for="'.$question->id.'"
                   class="block text-sm font-medium leading-6 dark:text-white">'.$question->question.' '.self::optional($question).'</label>
            '.self::hint($question).'
            <div class="mt-2">';

        if ($type === 'text') {
            $html .= '<div
                    class="flex rounded-md bg-white/5 ring-1 ring-inset ring-white/10 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                    <input type="text" name="questions['.$question->id.']" id="'.$question->id.'" autocomplete="off" '.self::required($question).'
                           class="flex-1 rounded-md dark:border-0 dark:bg-transparent py-1.5 pl-1 dark:text-white focus:ring-0 sm:text-sm sm:leading-6">
                </div>';
        } elseif ($type === 'textarea') {
            $html .= '<textarea id="'.$question->id.'" name="questions['.$question->id.']" '.self::required($question).' rows="3" class="block w-full rounded-md border-0 bg-white/5 py-1.5 dark:text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"></textarea>';
        }

        return $html.'</div></div>';
    }

    public static function select(ServiceQuestion $question)
    {
        $select = '<div class="sm:col-span-3">
          <label for="'.$question->id.'" class="block text-sm font-medium leading-6 dark:text-white">'.$question->question.' '.self::optional($question).'</label>
          '.self::hint($question).'
          <div class="mt-2">
            <select id="'.$question->id.'" name="questions['.$question->id.']" autocomplete="off" '.self::required($question).' class="block w-full rounded-md dark:border-0 dark:bg-white/5 py-1.5 dark:text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 [&_*]:text-black">
            <option class="dark:text-white dark:bg-transparent"></option>';

        foreach ($question->type_meta as $meta) {
            $select .= '<option value="'.$meta.'" class="dark:text-white dark:bg-transparent">'.$meta.'</option>';
        }
        $select .= '</select></div></div>';

        return $select;
    }

    public static function toggle(ServiceQuestion $question)
    {
        return '<div class="mt-10 space-y-10 border-b border-white/10 pb-12 flex items-center justify-between">
                  <span class="flex flex-grow flex-col">
                    <span class="text-sm font-semibold leading-6 dark:text-white" id="availability-label">' .
                        $question->question . ' ' . self::optional($question) . '</span>' .
                        self::hint($question) . '
                  </span>
                  <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                  <button type="button" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2" role="switch" aria-checked="false" aria-labelledby="availability-label" aria-describedby="availability-description">
                    <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 translate-x-0 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                  </button>
                </div>';
    }

    public static function checkbox(ServiceQuestion $question, $type = 'checkbox')
    {
        $checkbox = '<div class="mt-10 space-y-10 border-b border-white/10 pb-12">
        <fieldset>
          <legend class="text-sm font-semibold leading-6 dark:text-white"> '.$question->question.' '.self::optional($question).' </legend>
         '.self::hint($question).'
          <div class="mt-6 space-y-6">';

        foreach ($question->type_meta as $meta) {
            $checkbox .= '<div class="relative flex gap-x-3">
                  <div class="flex h-6 items-center">
                    <input id="'.$question->id.'-'.$meta.'" value="'.$meta.'" name="questions['.$question->id.']" type="'.$type.'" '.self::required($question).' class="h-4 w-4 rounded border-white/10 bg-white/5 text-indigo-600 focus:ring-indigo-600 focus:ring-offset-gray-900">
                  </div>
                  <div class="text-sm leading-6">
                    <label for="'.$question->id.'-'.$meta.'" class="font-medium dark:text-white">'.$meta.'</label>
                  </div>
                </div>';
        }

        $checkbox .= '</div>
        </fieldset>
      </div>';

        return $checkbox;
    }

    /**
     * Generate a hint that renders below the name of the form element
     *
     * @return string
     */
    public static function hint(ServiceQuestion $question)
    {
        return ($question->hint) ? '<p class="mt-1 text-sm leading-6 dark:text-gray-400">'.$question->hint.'</p>' : '';
    }

    /**
     * Generate an optional tag if the question is not required
     *
     * @return string
     */
    public static function optional(ServiceQuestion $question)
    {
        return ($question->required) ? '' : '<small>'.__('(optional)').'</small>';
    }

    public static function required(ServiceQuestion $question)
    {
        return ($question->required) ? 'required' : '';
    }
}
