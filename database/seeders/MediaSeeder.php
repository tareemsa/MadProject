<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Podcast;
use App\Models\Media;
use App\Enums\MediaTypeEnum;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        Podcast::all()->each(function ($podcast) {
            $podcast->media()->create([
                'file_name' => 'fake_' . $podcast->id . '.mp4',
                'file_path' => 'fake/media/fake_' . $podcast->id . '.mp4',
                'file_type' => MediaTypeEnum::VIDEO->value,
            ]);
        });
    }
}
