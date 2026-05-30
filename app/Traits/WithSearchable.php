<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait WithSearchable
{
    protected function scopeSearch(Builder $builder, mixed $term = '', mixed $columns = []): Builder
    {
        if (empty($columns)) {
            $columns = $this->searchable;
        }

        if (empty($columns)) {
            throw new Exception('No searchable columns found');
        }

        $term = mb_trim((string) $term);
        if ('' === $term) {
            return $builder;
        }

        $term = mb_strtolower($term);

        $builder->where(function ($query) use ($columns, $term): void {
            foreach ($columns as $column) {
                // Handle relationship columns (e.g., 'fromLocation.name->en')
                if (str_contains($column, '.')) {
                    [$relation, $field] = explode('.', $column, 2);

                    $query->orWhereHas($relation, function ($q) use ($field, $term): void {
                        // Check if the field is a JSON path
                        if (str_contains($field, '->')) {
                            [$jsonColumn, $jsonKey] = explode('->', $field, 2);
                            $q->where(DB::raw(sprintf("LOWER(JSON_UNQUOTE(JSON_EXTRACT(%s, '\$.%s')))", $jsonColumn, $jsonKey)), 'like', sprintf('%%%s%%', $term));
                        } else {
                            $q->where(DB::raw(sprintf('LOWER(%s)', $field)), 'like', sprintf('%%%s%%', $term));
                        }
                    });

                    continue;
                }

                if (str_contains($column, '->')) {
                    // Split the column name and JSON key
                    [$jsonColumn, $jsonKey] = explode('->', $column);
                    $query->orWhere(DB::raw(sprintf("LOWER(JSON_UNQUOTE(JSON_EXTRACT(%s, '\$.%s')))", $jsonColumn, $jsonKey)), 'like', sprintf('%%%s%%', $term));
                } else {
                    $query->orWhere(DB::raw(sprintf('LOWER(%s)', $column)), 'like', sprintf('%%%s%%', $term));
                }
            }
        });

        return $builder;
    }
}
