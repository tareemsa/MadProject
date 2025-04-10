<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreMediaRequest;
use App\Services\MediaService;
use App\Traits\ApiResponse;

class MediaController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected MediaService $mediaService) {}

    public function store(StoreMediaRequest $request): JsonResponse
    {
        $response = $this->mediaService->storeForUser(auth()->user(), $request->file('image'));
        return $this->success($response['data'], $response['message']);
    }
}


