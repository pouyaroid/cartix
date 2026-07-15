<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\QrCode;
use Illuminate\Database\Eloquent\Collection;

interface QrCodeRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?QrCode;
    public function findByUniqueCode(string $code): ?QrCode;
    public function create(array $data): QrCode;
    public function update(int $id, array $data): QrCode;
    public function delete(int $id): bool;
    public function getByUser(int $userId): Collection;
}
