<?php 
namespace App\Services;

use App\Models\User;

use App\Services\CommentableResolverService;
class CommentService
{
    public function __construct(
        protected CommentableResolverService $resolver
    ) {}

    public function store(User $user, array $data): array
    {
        $commentable = $this->resolver->resolve('podcast', $data['podcast_id']);

        $comment = $commentable->comments()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return [
            'data' => $comment->load('user'),
            'message' => 'Comment added successfully.',
            'code' => 201,
        ];
    }
}
 