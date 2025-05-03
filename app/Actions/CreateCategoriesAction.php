<?php
namespace App\Actions;

use App\Models\Category;

class CreateCategoriesAction
{
    public function execute(array $names): array
    {
        $ids = [];

        foreach ($names as $name) {
            $category = Category::firstOrCreate(['name' => $name]);
            $ids[] = $category->id;
        }

        return $ids;
    }
}
