<?php


namespace App\Http\Forms;


class SearchForm
{
    public function __construct(private array $formData)
    {
    }

    public function getCategory(): string
    {
        return $this->formData['cat'] ?? '';
    }

    public function getCity(): string
    {
        return $this->formData['city'] ?? '';
    }

    public function isSortByRating(): bool
    {
        return isset($this->formData['sort']) && $this->formData['sort'] === 'rating';
    }

    public function isSortByProjectsCount(): bool
    {
        return isset($this->formData['projects']) && $this->formData['projects'] === 'rating';
    }

    public function getSortingDir(): string
    {
        return $this->formData['dir'] ?? 'desc';
    }
}
