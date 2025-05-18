<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Http\Requests\SearchRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;


class SearchController extends Controller
{
    use ApiResponseTrait;

    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $result = $this->searchService->search($request->input('query'));
        return self::Success($result['data'], $result['message'], $result['code']);
    }
}
