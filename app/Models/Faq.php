<?php

namespace App\Models;

use App\Traits\WithSearchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations;
    use WithSearchable;

    public array $translatable = ['question', 'answer'];

    protected $fillable = [
        'question',
        'answer',
    ];

    protected $searchable = ['question->en', 'question->ckb', 'question->ar', 'answer->en', 'answer->ckb', 'answer->ar'];

    protected function casts()
    {
        return [
            'question' => 'array',
            'answer' => 'array',
        ];
    }
}
