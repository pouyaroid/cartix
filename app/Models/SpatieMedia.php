<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMediaModel;

class SpatieMedia extends SpatieMediaModel
{
    protected $table = 'spatie_media';
}
