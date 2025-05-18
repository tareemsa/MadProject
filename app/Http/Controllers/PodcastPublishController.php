<?php

namespace App\Http\Controllers;

use App\Services\Podcast\PodcastPublishService;
use App\Http\Requests\SchedulePublishPodcastRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class PodcastPublishController extends Controller
{
    use ApiResponseTrait;

    protected PodcastPublishService $publishService;

    public function __construct(PodcastPublishService $publishService)
    {
        $this->publishService = $publishService;
    }

    public function schedule(SchedulePublishPodcastRequest $request): JsonResponse
    {
        $result = $this->publishService->schedulePublish(
            $request->podcast_id,
            $request->channel_id,
            $request->publish_at,
        );

        return self::Success($result['data'], $result['message'], $result['code']);
    }
}
