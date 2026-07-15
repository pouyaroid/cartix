<?php

declare(strict_types=1);

namespace App\Traits;

trait HasActivityLog
{
    public function logActivity(string $event, array $properties = []): void
    {
        activity()
            ->performedOn($this)
            ->event($event)
            ->properties(array_merge($properties, [
                'model' => class_basename(static::class),
                'id' => $this->getKey(),
            ]))
            ->log("Model {$event}: " . class_basename(static::class) . " #{$this->getKey()}");
    }
}
