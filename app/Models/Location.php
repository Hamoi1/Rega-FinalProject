<?php

namespace App\Models;

use App\Traits\WithSearchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Location extends Model
{
    use HasTranslations;
    use WithSearchable;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'city_id',
        'map_location',
    ];

    protected $searchable = ['name->en', 'name->ckb', 'name->ar'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<City, $this>
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Favorite, $this>
     */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Favorite::class, 'location_id');
    }

    protected function casts(): array
    {
        return [
            'map_location' => 'array',
        ];
    }
}
