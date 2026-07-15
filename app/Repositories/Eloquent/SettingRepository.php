<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    public function __construct(
        protected Setting $model = new Setting,
    ) {}

    public function get(string $group, string $key, mixed $default = null): mixed
    {
        $setting = $this->model->where('group', $group)->where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public function set(string $group, string $key, mixed $value): void
    {
        $this->model->updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value]
        );
    }

    public function getGroup(string $group): array
    {
        return $this->model->where('group', $group)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }
}
