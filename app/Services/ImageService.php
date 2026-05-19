<?php

namespace App\Services;

use App\Models\Image;
use App\Models\User;
use App\Repositories\ImageRepository;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image as ImageFacade;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;




class ImageService
{
    public function __construct(private ImageRepository $repository) {}

    public function upload(UploadedFile $file, User $user): Image
    {
        $hash = md5_file($file->getRealPath());

        $existing = $this->repository->findByHash($hash);
        if ($existing) {
            return $this->repository->create($user->id, $existing->path, $hash);
        }

        $path = $this->compress($file);
        return $this->repository->create($user->id, $path, $hash);
    }

    private function compress(UploadedFile $file): string
    {
        $filename = uniqid() . '.webp';
        $path = storage_path('app/public/images/' . $filename);

        ImageFacade::make($file)->encode('webp', 85)->save($path);

        return 'images/' . $filename;
    }

    public function getList(User $user): Collection
    {
        return $this->repository->findByUser($user->id);
    }

    public function getImage(int $id, User $user): Image
    {
        $image = $this->repository->findById($id);

        if (!$image || $image->user_id !== $user->id) {
            abort(403, 'Нет доступа');
        }

        return $image;
    }

    public function delete(int $id, User $user): void
    {
        $image = $this->getImage($id, $user);

        $othersUsingFile = $this->repository->countByHash($image->hash);

        if ($othersUsingFile <= 1) {
            Storage::delete($image->path);     }

        $this->repository->delete($image);
    }
}
