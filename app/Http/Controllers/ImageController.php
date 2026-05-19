<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;




class ImageController extends Controller
{
    public function __construct(private ImageService $service) {

    }

    public function upload(UploadImageRequest $request)
    {
        $image = $this->service->upload($request->file('image'), auth()->user());
        return response()->json($image, 201);
    }

    public function index(): JsonResponse
    {
        $images = $this->service->getList(auth()->user());
        return response()->json($images);
    }

    public function show(int $image): BinaryFileResponse
    {
        $image = $this->service->getImage($image, auth()->user());
        return response()->file(storage_path('app/public/' . $image->path));
    }

    public function destroy(int $image): JsonResponse
    {
        $this->service->delete($image, auth()->user());
        return response()->json(['message' => 'Удалено']);
    }


}
