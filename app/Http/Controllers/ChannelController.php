<?php
namespace App\Http\Controllers;
use App\Services\ChannelService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ChannelRequest;
class ChannelController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ChannelService $channelService) {}

    public function create(ChannelRequest $request): JsonResponse
    {
        $result = $this->channelService->create(auth()->user(), $request->validated());
    
        return self::Success($result['data'], $result['message'], $result['code']);
    }
}