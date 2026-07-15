<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\QrCode;
use App\Repositories\Contracts\QrCodeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QrCodeRepository implements QrCodeRepositoryInterface
{
    public function __construct(
        protected QrCode $model = new QrCode,
    ) {}

    public function all(): Collection
    {
        return $this->model->with(['user', 'card'])->get();
    }

    public function find(int $id): ?QrCode
    {
        return $this->model->with(['user', 'card'])->find($id);
    }

    public function findByUniqueCode(string $code): ?QrCode
    {
        return $this->model->where('unique_code', $code)->where('is_active', true)->first();
    }

    public function create(array $data): QrCode
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): QrCode
    {
        $qr = $this->find($id);
        $qr->update($data);
        return $qr->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->findOrFail($id)->delete();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with('card')->latest()->get();
    }
}
