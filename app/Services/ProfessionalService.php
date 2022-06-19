<?php


namespace App\Services;


use App\Http\Forms\SearchForm;
use App\Models\Professional;
use App\Models\Project;
use App\Models\User;
use App\Modules\Images\ProjectImage;
use App\Repositories\ProfessionalRepository;
use App\Services\Images\ImageHandlerInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ProfessionalService
{
    private ImageHandlerInterface $professionalImage;

    public function __construct(
        private ProfessionalRepository $professionalRepository,
        private ImageHandler $imageHandler
    ) {
        $this->professionalImage = $this->imageHandler->professional();
    }

    public function create(User $user, array $data, UploadedFile $logo): Professional
    {
        $data['uid'] = (int)(time() . rand(100, 999));
        $logoPath = $this->professionalImage->uploadImage($logo);

        $data['logo'] = $logoPath;
        $data['offer_execution'] = false;

        $professional = $this->professionalRepository->create($data);
        $professional->categories()->sync($data['categories']);

        $professional->users()->attach($user->id);

        return $professional;
    }

    public function getTopRated(): Collection
    {
        return $this->professionalRepository->getTopRated();
    }

    public function getByUid(int $uid): ?Professional
    {
        return $this->professionalRepository->getByUid($uid);
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

    public function update(Professional $professional, array $data, ?UploadedFile $logo = null): Professional
    {
        unset($data['logo']);
        $oldImage = $professional->logo;
        if ($logo) {
            $data['logo'] = $this->professionalImage->uploadImage($logo);
        }

        $professional = $this->professionalRepository->update($professional, $data);
        if (isset($data['categories'])) {
            $professional->categories()->sync($data['categories']);
        }

        if ($logo) {
            $this->professionalImage->removeImage($oldImage);
        }

        return $professional;
    }

    public function ownProject(?Professional $professional, ?Project $project): bool
    {
        if (!$professional || !$project) {
            return false;
        }

        return (bool) $professional->projects()->where('id', $project->id)->first();
    }

    public function updateImages(Project $project, array $imagesData): bool
    {
        foreach ($imagesData as $image) {
            \App\Models\ProjectImage::query()->where('id', $image['id'])
                ->update([
                    'title' => $image['title'],
                    'space_id' => empty($image['space_id']) ? null : $image['space_id'],
                    'design_type_id' => empty($image['design_type_id']) ? null : $image['design_type_id'],
                    'description' => $image['description'],
                    'palette' => $image['palette'],
                ]);
        }

        return true;
    }
}
