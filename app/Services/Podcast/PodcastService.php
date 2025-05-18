<?php

namespace App\Services\Podcast;

use App\Models\Podcast;
use App\Model\User;
use Illuminate\Http\UploadedFile;
use App\Services\MediaService;
use App\Services\CommentService;
use App\Actions\ToggleLikeAction;
use App\Actions\SyncPodcastCategoriesAction;
use App\Actions\CreateCategoriesAction;

class PodcastService
{
    public function __construct(
        protected MediaService $mediaService,
        protected CommentService $commentService,
        protected ToggleLikeAction $toggleLikeAction,
        protected SyncPodcastCategoriesAction $syncAction,
        protected CreateCategoriesAction $createAction
    ) {}
    public function uploadPodcast(User $user, array $data): array
    {
        $podcast = $user->podcasts()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);
    
        if (isset($data['cover_image'])) {
            $this->mediaService->storeForModel($podcast, $data['cover_image'], 'cover');
        }
    
        if (isset($data['podcast_file'])) {
            $mime = $data['podcast_file']->getMimeType();
            $fileType = str_starts_with($mime, 'video/') ? 'video' : 'audio';
    
            $this->mediaService->storeForModel($podcast, $data['podcast_file'], $fileType);
        }
    
        return [
            'data' => ['podcast' => $podcast->load('media')],
            'message' => 'Podcast uploaded successfully.',
            'code' => 201,
        ];
    }
    public function getPodcastWithAllNestedComments(Podcast $podcast): array
    {
        $podcast->load(['media']);
        $comments = $podcast->comments->get(); 


        //$commentsWithNested = $this->commentService->formatCommentsRecursively($comments);

        return [
            'data' => [
                'podcast' => $podcast,
                'comments' => CommentResource::collection($comments),
            ],
            'message' => 'Podcast with fully nested comments retrieved successfully.',
            'code' => 200,
        ];
    }
    
    public function toggleLike(Podcast $podcast, $user): array
    {
        $liked = $this->toggleLikeAction->execute($user, $podcast);

        return [
            'liked' => $liked,
            'message' => $liked ? 'Podcast liked successfully.' : 'Podcast unliked successfully.',
            'code' => 200,
        ];
    }
    
    public function syncCategories(Podcast $podcast, array $data): array
    {
        $categoryIds = $data['category_ids'] ?? [];
        $categoryNames = $data['category_names'] ?? [];

        if (!empty($categoryNames)) {
            $newIds = $this->createAction->execute($categoryNames);
            $categoryIds = array_merge($categoryIds, $newIds);
        }

        if (empty($categoryIds)) {
            return [
                'data' => [],
                'message' => 'No valid categories provided.',
                'code' => 400
            ];
        }

        $this->syncAction->execute($podcast, $categoryIds);

        return [
            'data' => $podcast->load('categories'),
            'message' => 'Categories synced successfully.',
            'code' => 200
        ];
    }

    /*public function getRandomPodcasts(int $count = 10): array
    {
        $randomIds = cache('random_podcast_ids');
    
        if (empty($randomIds)) {
            $randomIds = Podcast::inRandomOrder()
                ->limit(50)
                ->pluck('id')
                ->toArray();
    
            cache()->put('random_podcast_ids', $randomIds, now()->addMinutes(5));
        }
    
        $selected = collect($randomIds)->shuffle()->take($count);
    
        $podcasts = Podcast::with(['media', 'categories', 'user'])
            ->whereIn('id', $selected)
            ->get();
    
        return [
            'data' => $podcasts,
            'message' => 'Random podcasts retrieved successfully.',
            'code' => 200
        ];
    }*/
    public function getRandomPodcasts(int $count = 10): array
    {
        $randomIds = cache('random_podcast_ids');
    
        if (empty($randomIds)) {
            $randomIds = Podcast::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->inRandomOrder()
                ->limit(50)
                ->pluck('id')
                ->toArray();
    
            cache()->put('random_podcast_ids', $randomIds, now()->addMinutes(5));
        }
    
        $selected = collect($randomIds)->shuffle()->take($count);
    
        $podcasts = Podcast::with(['media', 'categories', 'user'])
            ->whereIn('id', $selected)
            ->get();
    
        return [
            'data' => $podcasts,
            'message' => 'Random podcasts retrieved successfully.',
            'code' => 200
        ];
    }
    

    

/*public function filterByCategory(array $filters): array
{

$query = Podcast::query();

if (!empty($filters['category_ids'])) {
    $query->whereHas('categories', function ($q) use ($filters) {
        $q->whereIn('categories.id', $filters['category_ids']);
    });
}

$podcasts = $query->paginate(10);

return [
    'data' => $podcasts,
    'message' => 'Filtered podcasts retrieved.',
    'code' => 200
];
}*/
}