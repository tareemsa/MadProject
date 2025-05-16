<?php

namespace App\Http\Controllers;
use App\Services\SubscriptionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Models\Channel;

class SubscriptionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected SubscriptionService $subscriptionService) {}

    public function toggle(Channel $channel): JsonResponse
    {
        $result = $this->subscriptionService->toggleSubscription(auth()->user(), $channel);

        return self::Success($result['data'], $result['message'], $result['code']);
    }
}