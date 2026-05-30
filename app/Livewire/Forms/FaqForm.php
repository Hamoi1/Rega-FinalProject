<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class FaqForm extends Form
{
    public $edit = false;

    public $faq_id;

    public $question_en;

    public $question_ar;

    public $question_ckb;

    public $answer_en;

    public $answer_ar;

    public $answer_ckb;

    protected function rules(): array
    {
        return [
            'question_en' => ['required', 'string', 'max:2000'],
            'question_ar' => ['required', 'string', 'max:2000'],
            'question_ckb' => ['required', 'string', 'max:2000'],
            'answer_en' => ['required', 'string', 'max:4000'],
            'answer_ar' => ['required', 'string', 'max:4000'],
            'answer_ckb' => ['required', 'string', 'max:4000'],
        ];
    }
}
