<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Card;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CardRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Card;
    public function findBySlug(string $slug): ?Card;
    public function create(array $data): Card;
    public function update(int $id, array $data): Card;
    public function delete(int $id): bool;
    public function getByUser(int $userId): Collection;
    public function getPublished(): Collection;
    public function getFeatured(): Collection;
    public function search(string $query): LengthAwarePaginator;
    public function paginated(int $perPage = 15): LengthAwarePaginator;
}
