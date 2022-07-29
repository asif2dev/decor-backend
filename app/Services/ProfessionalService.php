<?php


namespace App\Services;


use App\Http\Forms\SearchForm;
use App\Models\Professional;
use App\Models\Project;
use App\Models\User;
use App\Modules\Images\ProjectImage;
use App\Repositories\ProfessionalRepository;
use App\Services\Images\ImageHandlerInterface;
use App\Support\Str;
use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Throwable;

class ProfessionalService
{
    private ImageHandlerInterface $professionalImage;

    public function __construct(
        private ProfessionalRepository $professionalRepository,
        private ImageHandler $imageHandler
    ) {
        $this->professionalImage = $this->imageHandler->professional();
    }

    public function syncCategories(Professional $professional, array $categoriesIds): void
    {
        $professional->categories()->sync($categoriesIds);
    }

    public function attachUser(Professional $professional, int $userId): void
    {
        $professional->users()->attach($userId);
    }

    public function updateLogo(Professional $professional, UploadedFile $logo): void
    {
        $logoPath = $this->professionalImage->uploadImage($logo);
        $professional->logo = $logoPath;
        $professional->save();
    }

    public function updateSlug(Professional $professional): void
    {
        $professional->slug = Str::arSlug($professional->company_name) . '-' . $professional->id . rand(100, 999);
        $professional->save();
    }

    public function getTopRated(): Collection
    {
        return $this->professionalRepository->getTopRated();
    }

    public function getByUid(int $uid): ?Professional
    {
        return $this->professionalRepository->getByUid($uid);
    }

    public function getBySlug(string $slug): ?Professional
    {
        return $this->professionalRepository->getBySlug($slug);
    }

    public function hasUser(Professional $professional, User $user): bool
    {
        $user = $professional->users()->where('users.id', $user->id)->first();

        return (bool) $user;
    }

    public function search(SearchForm $searchForm): Collection
    {
        return $this->professionalRepository->search($searchForm);
    }

    public function ownProject(?Professional $professional, ?Project $project): bool
    {
        if (!$professional || !$project) {
            return false;
        }

        return (bool) $professional->projects()->where('id', $project->id)->first();
    }
}
