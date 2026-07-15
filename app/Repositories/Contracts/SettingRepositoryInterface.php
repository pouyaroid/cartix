<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface
{
    public function get(string $group, string $key, mixed $default = null): mixed;
    public function set(string $group, string $key, mixed $value): void;
    public function getGroup(string $group): array;
}
