<?php

namespace App\Services\Podcast;

use App\Models\Podcast;
use App\Models\PodcastView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PodcastFilterService
{
    /**
     * Extract filters from the request.
     */
    public function getFilters(Request $request): array
    {
        return [
            'category_ids' => $request->query('category_ids', []),
            'per_page'     => $request->query('per_page', 10),
            'page'         => $request->query('page', 1),
            'sort'         => $request->query('sort', null), // most_viewed | trending
        ];
    }


    public function recordView(User $user, Podcast $podcast): array
    {
        $alreadyViewed = PodcastView::where('user_id', $user->id)
            ->where('podcast_id', $podcast->id)
            ->exists();

        if (! $alreadyViewed) {
            PodcastView::create([
                'user_id'    => $user->id,
                'podcast_id' => $podcast->id,
                'viewed_at'  => now(),
            ]);
        }

        return [
            'data'    => [],
            'message' => $alreadyViewed ? 'View already recorded.' : 'Podcast view recorded.',
            'code'    => 200,
        ];
    }

    public function filterByCategoryWithMetrics(array $filters): array
    {
        $query = Podcast::with(['media', 'categories', 'user']);

        // Filter by category
        if (!empty($filters['category_ids'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->whereIn('categories.id', $filters['category_ids']);
            });
        }

        // Sorting by most viewed
        if ($filters['sort'] === 'most_viewed') {
            $query->withCount('views')->orderBy('views_count', 'desc');
        }

        // Sorting by trending (last 7 days)
        if ($filters['sort'] === 'trending') {
            $query->withCount([
                'views as recent_views_count' => function ($q) {
                    $q->where('viewed_at', '>=', now()->subDays(7));
                }
            ])->orderByDesc('recent_views_count');
        }

        $paginated = $query->paginate($filters['per_page'] ?? 10);

        return [
            'data'    => $paginated,
            'message' => 'Filtered podcasts retrieved successfully.',
            'code'    => 200,
        ];
    }

    public function mostViewedPodcastsQuery()
    {
        return Podcast::with(['media', 'categories', 'user'])
            ->withCount('views')
            ->orderByDesc('views_count');
    }

    public function trendingPodcastsQuery()
    {
        $sevenDaysAgo = now()->subDays(7);

        return Podcast::with(['media', 'categories', 'user'])
            ->whereHas('views', function ($query) use ($sevenDaysAgo) {
                $query->where('viewed_at', '>=', $sevenDaysAgo);
            })
            ->withCount(['views as recent_views_count' => function ($query) use ($sevenDaysAgo) {
                $query->where('viewed_at', '>=', $sevenDaysAgo);
            }])
            ->orderByDesc('recent_views_count');
    }
}
