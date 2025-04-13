<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Handle exceptions and log them
     *
     * @param callable $callback
     * @return mixed
     */
    protected function handleExceptions(callable $callback)
    {
        try {
            return $callback();
        } catch (Exception $e) {
            Log::error('Repository Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            abort(500, $e->getMessage());
            // abort(500, 'Something went wrong. Please try again later.');
        }
    }

    public function all(array $columns = ['*']): iterable
    {
        return $this->handleExceptions(function () use ($columns) {
            return $this->model->all($columns);
        });
    }


    public function paginated(array $columns = ['*'], $perPage = 10, $where = []): iterable
    {
        return $this->handleExceptions(function () use ($columns, $where, $perPage) {
            return $this->model->select(($columns))->where($where)->latest()->paginate($perPage);
        });
    }

    public function find(string $id, array $columns = ['*'])
    {
        return $this->handleExceptions(function () use ($id, $columns) {
            return $this->model->where('id', $id)->first($columns); // Changed to first() instead of firstOrFail()
        });
    }


    public function findById(int $id)
    {
        return $this->handleExceptions(function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }

    public function create(array $data)
    {
        return $this->handleExceptions(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(string $id, array $data)
    {
        return $this->handleExceptions(function () use ($id, $data) {
            $model = $this->model->where('id', $id)->firstOrFail();
            $model->update($data);
            return $model;
        });
    }

    public function delete(string $id): bool
    {
        return $this->handleExceptions(function () use ($id) {
            $model = $this->model->where('id', $id)->firstOrFail();
            return $model->delete();
        });
    }


    function getDataTable(Model $model, $orsFilters = [], $andsFilters = [], $relations = [], $searchingColumns = null, $withTrashed = false, $orderBy = [], $group = null, $Between = [], $subRelation = [], $searchColumns = [])
    {
        $columns = $searchingColumns ?? $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        $relationsWithColumns = getRelationWithColumns($relations);

        /** Get the request parameters **/
        $params = request()->all();
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 10;

        // Initialize the query
        $model = $withTrashed ? $model->query()->onlyTrashed() : $model->query();
        $model->with($relationsWithColumns);

        /** General search **/
        if (isset($params['search']['value']) && count($searchColumns)) {
            $searchValue = $params['search']['value'];
            // Check if the search value contains special characters
            if (preg_match('/[^a-zA-Z0-9\s]/', $searchValue)) {
                // Use LIKE for special characters
                $model->where(function ($query) use ($searchColumns, $searchValue) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'like', "%$searchValue%");
                    }
                });
            } else {
                // Use MATCH for full-text search
                $columnsForMatch = implode(', ', $searchColumns);
                $model->whereRaw("MATCH($columnsForMatch) AGAINST(? IN BOOLEAN MODE)", [$searchValue]);
            }
        }

        // Apply OR filters
        if (!empty($orsFilters)) {
            $model->where(function ($q) use ($orsFilters) {
                foreach ($orsFilters as $field => $value) {
                    $q->orWhere($field, "like", "%" . $value . "%");
                }
            });
        }

        // Apply AND filters
        if (!empty($andsFilters)) {
            foreach ($andsFilters as $field => $value) {
                $model->where($field, $value);
            }
        }

        // Apply date range filter (BETWEEN)
        if (!empty($Between)) {
            foreach ($Between as $field => $range) {
                if (is_array($range) && count($range) === 2) {
                    $model->whereBetween($field, $range);
                }
            }
        }

        // Apply grouping
        if (!empty($group)) {
            $model->groupBy($group);
        }

        // Apply ordering
        if (!empty($orderBy)) {
            foreach ($orderBy as $column => $direction) {
                $model->orderBy($column, $direction);
            }
        } else {
            $model->orderBy('id', 'desc'); // Default ordering
        }


        /** Filter search **/
        if (isset($params['columns'])) {
            foreach ($params['columns'] as $column) {
                if ($column['search']['value'] && $column['search']['value'] != 'all') {
                    if ($column['name'] == 'created_at' || $column['name'] == 'date') {
                        if (!str_contains($column['search']['value'], ' - ')) {
                            $model->whereDate($column['name'], $column['search']['value']);
                        } else {
                            $model->whereBetween($column['name'], getDateRangeArray($column['search']['value']));
                        }
                    } else {
                        $model->where($column['name'], '=', $column['search']['value']);
                    }
                }
            }
        }

        /** Paginate the results **/
        $data = $model->paginate($perPage, ['*'], 'page', $page);

        $response = [
            "recordsTotal"    => $data->total(),
            "recordsFiltered" => $data->total(),
            'data'            => $data->items(),
        ];

        return $response;
    }
}
