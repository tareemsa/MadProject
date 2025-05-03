<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; 
use App\Services\Podcast\PodcastService;
use App\Http\Requests\PodcastUploadRequest;
use App\Traits\ApiResponseTrait;
use App\Models\Podcast;
use App\Models\User;
use App\Http\Requests\PodcastCategoryRequest;
use App\Services\Podcast\PodcastFilterService;
class PodcastController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected PodcastService $podcastService,
        protected PodcastFilterService $filterService
     
    ) {}
    
    public function store(PodcastUploadRequest $request): JsonResponse
    {
        $result = $this->podcastService->uploadPodcast(auth()->user(), $request->all());

        return self::Success($result['data'], $result['message'], $result['code']);
    }


    public function showWithAllNestedComments(Podcast $podcast): JsonResponse
    {
        $result = $this->podcastService->getPodcastWithAllNestedComments($podcast);

        return self::Success(
            $result['data'],
            $result['message'],
            $result['code']
        );
    }

    public function toggleLike(Podcast $podcast): JsonResponse
    {
        $result = $this->podcastService->toggleLike($podcast, auth()->user());

        return self::Success(
            ['liked' => $result['liked']],
            $result['message'],
            $result['code']
        );
    }

    public function updateCategories(PodcastCategoryRequest $request, Podcast $podcast): JsonResponse
{
    $result = $this->podcastService->syncCategories($podcast, $request->validated());

    return self::Success($result['data'], $result['message'], $result['code']);
}
public function random(Request $request): JsonResponse
{
    $perPage = $request->query('per_page', 10);

    $result = $this->podcastService->getRandomPodcasts($perPage);

    return self::Success($result['data'], $result['message'], $result['code']);
}
/*public function filterPodcastsByCategory(Request $request): JsonResponse
{

    $filters = $this->filterService->getFilters($request);

    $result = $this->filterService->filterByCategory($filters);

    return self::Success($result['data'], $result['message'], $result['code']);
}*/
public function view(Request $request, Podcast $podcast): JsonResponse
{
    $result = $this->filterService->recordView($request->user(), $podcast);

    return self::Success($result['data'], $result['message'], $result['code']);
}
/*public function mostViewed(Request $request): JsonResponse
{
    $result = $this->filterService->getMostViewedPodcasts(
        $request->query('per_page', 10)
    );

    return self::Success($result['data'], $result['message'], $result['code']);
}
*/
/*public function trending(Request $request): JsonResponse
{
    $perPage = $request->query('per_page', 10);
    $result = $this->filterService->getTrendingPodcasts($perPage);

    return self::Success($result['data'], $result['message'], $result['code']);
}*/


public function filterByCategoryWithMetrics(Request $request): JsonResponse
{
    $filters = $this->filterService->getFilters($request);

    $result = $this->filterService->filterByCategoryWithMetrics($filters);

    return self::Success($result['data'], $result['message'], $result['code']);
}


}
