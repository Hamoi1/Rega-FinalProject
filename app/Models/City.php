<?php

namespace App\Models;

use App\Traits\WithSearchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;
    use WithSearchable;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
    ];

    protected $searchable = ['name->en', 'name->ckb', 'name->ar'];


    protected function casts()
    {
        return [
            'name' => 'array',
        ];
    }
}
