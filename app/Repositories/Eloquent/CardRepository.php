<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Card;
use App\Repositories\Contracts\CardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CardRepository implements CardRepositoryInterface
{
    public function __construct(
        protected Card $model = new Card,
    ) {}

    public function all(): Collection
    {
        return $this->model->with(['user', 'template'])->get();
    }

    public function find(int $id): ?Card
    {
        return $this->model->with(['user', 'template', 'sections', 'socialLinks', 'galleryItems'])->find($id);
    }

    public function findBySlug(string $slug): ?Card
    {
        return $this->model->with(['user', 'template', 'sections', 'socialLinks', 'galleryItems', 'products', 'services', 'testimonials', 'faqs'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Card
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Card
    {
        $card = $this->find($id);
        $card->update($data);
        return $card->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->findOrFail($id)->delete();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with('template')->latest()->get();
    }

    public function getPublished(): Collection
    {
        return $this->model->published()->with(['user', 'template'])->get();
    }

    public function getFeatured(): Collection
    {
        return $this->model->featured()->published()->with(['user', 'template'])->get();
    }

    public function search(string $query): LengthAwarePaginator
    {
        return $this->model->where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('slug', 'like', "%{$query}%");
        })->with(['user', 'template'])->paginate(15);
    }

    public function paginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'template'])->latest()->paginate($perPage);
    }
}
