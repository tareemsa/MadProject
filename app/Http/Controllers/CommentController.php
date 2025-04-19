<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected CommentService $commentService
    ) {}

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $result = $this->commentService->store(auth()->user(), $request->validated());

        return self::Success($result['data'], $result['message'], $result['code']);
    }
}
