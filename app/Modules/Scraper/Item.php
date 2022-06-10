<?php

namespace App\Modules\Scraper;

class Item
{
    public string $link;

    public string $title;

    public array $price;

    public string $image;

    public string $category;

    public string $parentCategory;

    public string $categoryLink;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
