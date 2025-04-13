<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']): iterable;

    public function paginated(array $columns = ['*'], $perPage, $where = []): iterable;

    public function find(string $id, array $columns = ['*']);

    public function findById(int $id);

    public function create(array $data);

    public function update(string $id, array $data);

    public function delete(string $id): bool;
}
