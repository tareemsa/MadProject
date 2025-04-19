<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; 
use App\Services\Podcast\PodcastService;
use App\Http\Requests\PodcastUploadRequest;
use App\Traits\ApiResponseTrait;
use App\Models\Podcast;

class PodcastController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected PodcastService $podcastService
    ) {}
    
    public function store(PodcastUploadRequest $request): JsonResponse
    {
        $result = $this->podcastService->uploadPodcast(auth()->user(), $request->all());

        return self::Success($result['data'], $result['message'], $result['code']);
    }


    public function showWithComments(Podcast $podcast)
{
    return response()->json([
        'podcast' => $podcast->load([
            'media',
            'comments.user',
            'comments.replies.user', 
        ])
    ]);
}

    
}
