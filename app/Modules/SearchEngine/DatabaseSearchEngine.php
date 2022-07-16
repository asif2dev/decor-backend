<?php


namespace App\Modules\SearchEngine;


use App\Http\Forms\InspireSearchForm;
use App\Http\Forms\SearchForm;
use App\Models\ProjectImage;
use App\Models\Space;
use App\Services\ProfessionalService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DatabaseSearchEngine implements SearchEngineInterface
{
    public function __construct(private ProfessionalService $professionalService)
    {
    }

    public function search(SearchForm $searchForm): Collection
    {
        return $this->professionalService->search($searchForm);
    }

    public function getTopRated(): Collection
    {
        return $this->professionalService->getTopRated();
    }

    public function configure(): void
    {
        // TODO: Implement configure() method.
    }

    public function getImagesBySpace(string $space, int $limit = 4): Collection
    {
        /** @var Space $space */
        $space = Space::query()
            ->where('slug', $space)
            ->first();

        if (!$space) {
            return collect();
        }

        return $space->projectImages()
            ->inRandomOrder()
            ->with(['space', 'professional', 'designType'])
            ->skip(0)
            ->take($limit)
            ->get();
    }

    public function inspire(InspireSearchForm $searchForm): Collection
    {
        $result = ProjectImage::query()->inRandomOrder();
        $result = $result->when($searchForm->getSpace(), function (Builder $q) use ($searchForm) {
            $q->whereHas('space', function (Builder $builder) use ($searchForm) {
                $builder->where('spaces.slug', $searchForm->getSpace());
            });
        });

        $result = $result->when($searchForm->getDesignType(), function (Builder $q) use ($searchForm) {
            $q->whereHas('designType', function (Builder $builder) use ($searchForm) {
                $builder->where('design_types.slug', $searchForm->getDesignType());
            });
        });

        $result = $result->when($searchForm->getColor(), function (Builder $q) use ($searchForm) {
            $q->where('palette', 'like', "%{$searchForm->getColor()}%");
        });

        $result = $result->skip($searchForm->getStart())->take($searchForm->getPerPage());

        return $result->get();
    }
}
