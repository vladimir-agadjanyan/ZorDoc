<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

class ImageRepository
{
    public function findByUser(int $userId): Collection
    {
        return Image::where('user_id', $userId)->get();
    }

    public function findById(int $id): ?Image
    {
        return Image::find($id);
    }

    public function countByHash(string $hash): int
    {
        return Image::where('hash', $hash)->count();
    }
}
