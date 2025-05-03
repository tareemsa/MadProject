<?php
namespace App\Actions;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CustomException;

class ToggleLikeAction
{
    public function execute(User $user, Model $model): bool
    {
        if (! $user || ! $model) {
            throw new CustomException('User or podcast not found.', 404);
        }

        if (! method_exists($model, 'likes')) {
            throw new CustomException('This model does not support likes.', 400);
        }

        if ($model->isLikedBy($user)) {
            $model->likes()->where('user_id', $user->id)->delete();
            return false; 
        }

        $model->likes()->create([
            'user_id' => $user->id,
        ]);

        return true; 
    }
}
