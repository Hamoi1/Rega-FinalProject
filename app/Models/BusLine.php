<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\WithSearchable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Throwable;

class BusLine extends Model implements HasMedia
{
    use InteractsWithMedia;
    use WithSearchable;

    protected $fillable = [
        'from_location_id',
        'to_location_id',
        'status',
    ];

    protected $searchable = ['fromLocation.name->en', 'toLocation.name->en', 'fromLocation.name->ckb', 'toLocation.name->ckb','fromLocation.name->ar', 'toLocation.name->ar'];

    protected $appends = [
        // 'route_json_file',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Location, $this>
     */
    public function fromLocation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Location, $this>
     */
    public function toLocation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Favorite, $this>
     */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('route_json_file')
            ->singleFile();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($line): void {
            DB::beginTransaction();
            try {
                $line->clearMediaCollection('route_json_file');
                DB::commit();
            } catch (Throwable $throwable) {
                DB::rollBack();
                throw new Exception($throwable->getMessage(), $throwable->getCode(), $throwable);
            }
        });
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function active($query): mixed
    {
        return $query->where('status', StatusEnum::Active);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function inactive($query): mixed
    {
        return $query->where('status', StatusEnum::Inactive);
    }

    protected function routeJsonFile(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn($value): string => $this->getFirstMediaUrl('route_json_file') ?? null,
        );
    }

    protected function casts(): array
    {
        return [
            'status' => StatusEnum::class,
        ];
    }
}
