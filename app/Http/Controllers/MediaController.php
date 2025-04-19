<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreMediaRequest;
use App\Services\MediaService;
use App\Traits\ApiResponseTrait;

class MediaController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected MediaService $mediaService) {}


    public function uploadMedia(StoreMediaRequest $request): JsonResponse
    {
        $result = $this->mediaService->storeForModel(auth()->user(), $request->file('image'));
    
        return self::Success($result['data'], $result['message'], $result['code']);
    }
    }


