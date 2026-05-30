<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    protected const PER_PAGE = 50;

    protected string $locale = 'en';

    protected static array $request_params = [];

    protected static bool $haveJoin = false;

    protected string $selectedTable = '';

    private array $availableTables = ['users', 'cities', 'locations'];

    private array $availableLocales = ['en', 'ckb', 'ar'];

    public function __invoke(Request $request, string $table)
    {
        $this->selectedTable = $table;

        return $this->query($request);
    }

    private function getData($data): array
    {
        if ('users' === $this->selectedTable && null !== data_get(self::$request_params, 'column-username')) {
            $data->user_id = $data->username;
        }

        return match ($this->selectedTable) {
            'users' => [
                'id' => $data->user_id,
                'name' => data_get($data, 'name', __('words.unknown')),
            ],

            default => [
                'id' => $data->id,
                'name' => $data->name,
            ],
        };
    }

    private function getColumns(): array
    {
        $table = $this->selectedTable;

        return match ($table) {
            'users' => [
                'users.id as user_id',
                'users.name as name',
                'users.status',
                'users.username',
            ],

            'cities', 'locations' => [
                $table . '.id',
                DB::raw($this->translatedNameSelect($table . '.name') . ' as name'),
            ],

            default => [
                $table . '.id',
                $table . '.name',
            ],
        };
    }

    private function query(Request $request)
    {
        if ( ! in_array($this->selectedTable, $this->availableTables, true)) {
            return response()->json([
                'message' => __('api.errors.table_not_found'),
            ], 404);
        }

        self::$request_params = $request->all();

        if ($request->has('locale') && in_array($request->locale, $this->availableLocales, true)) {
            $this->locale = $request->locale;
            app()->setLocale($request->locale);
        }

        return DB::table($this->selectedTable)
            ->select($this->getColumns())
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = mb_strtolower(trim((string) $request->search));

                $query->where(function (Builder $query) use ($search): void {
                    foreach ($this->getColumnSearch() as $column) {
                        $query->orWhere($column, 'like', sprintf('%%%s%%', $search));
                    }
                });
            })
            ->when($request->exists('selected'), fn(Builder $query) => $query->when(
                is_array($request->selected),
                fn(Builder $query) => $query
                    ->orWhereIn($this->selectedTable . '.id', $request->selected)
                    ->orWhereIn($this->getColumnToSelect(), $request->selected),
                fn(Builder $query) => $query
                    ->orWhere($this->selectedTable . '.id', $request->selected)
                    ->orWhere($this->getColumnToSelect(), $request->selected),
            ))
            ->when($this->selectedTable, fn(Builder $query) => $this->QueryBuilderCheck($query))
            ->when(self::$request_params, fn(Builder $query) => $this->CheckParams($query))
            ->latest($this->selectedTable . '.created_at')
            ->take(self::PER_PAGE)
            ->get()
            ->map(fn($data): array => $this->getData($data))
            ->toArray();
    }

    private function getColumnSearch(): array
    {
        $table = $this->selectedTable;

        return match ($table) {
            'users' => [
                DB::raw('LOWER(' . $table . '.name)'),
                DB::raw('LOWER(' . $table . '.username)'),
            ],

            /**
             * Search cities and locations in ALL languages.
             *
             * Example:
             * name = {
             *   "en": "Sulaymaniyah",
             *   "ckb": "سلێمانی",
             *   "ar": "السليمانية"
             * }
             *
             * Now search works with any of them.
             */
            'cities', 'locations' => collect($this->availableLocales)
                ->map(fn(string $locale) => DB::raw(
                    'LOWER(JSON_UNQUOTE(JSON_EXTRACT(' . $table . '.name, "$.' . $locale . '")))',
                ))
                ->all(),

            default => [
                DB::raw('LOWER(' . $table . '.name)'),
            ],
        };
    }

    private function getColumnToSelect()
    {
        $table = $this->selectedTable;

        return match ($table) {
            'cities', 'locations' => DB::raw($this->translatedNameSelect($table . '.name')),

            default => DB::raw('LOWER(' . $table . '.name)'),
        };
    }

    private function translatedNameSelect(string $column): string
    {
        return 'COALESCE(
            JSON_UNQUOTE(JSON_EXTRACT(' . $column . ', "$.' . $this->locale . '")),
            JSON_UNQUOTE(JSON_EXTRACT(' . $column . ', "$.en")),
            JSON_UNQUOTE(JSON_EXTRACT(' . $column . ', "$.ckb")),
            JSON_UNQUOTE(JSON_EXTRACT(' . $column . ', "$.ar"))
        )';
    }

    private function QueryBuilderCheck(Builder $builder)
    {
        $table = $this->selectedTable;

        return match ($table) {
            default => $builder,
        };
    }

    private function CheckParams(Builder $query): Builder
    {
        $query->when(
            'locations' === $this->selectedTable,
            function (Builder $query): void {
                $query->when(
                    data_get(self::$request_params, 'city_id') && null !== data_get(self::$request_params, 'city_id'),
                    fn(Builder $query) => $query->where('city_id', data_get(self::$request_params, 'city_id')),
                );
            },
        );

        return $query;
    }
}
