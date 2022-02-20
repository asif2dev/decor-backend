<?php


namespace App\Services;


use App\Http\Forms\SearchForm;
use App\Models\Professional;
use App\Models\User;
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
        $oldImage = $professional->logo;
        if ($logo) {
            $data['logo'] = $this->professionalImage->uploadImage($logo);
        }

        $professional = $this->professionalRepository->update($professional, $data);
        $professional->categories()->sync($data['categories']);

        if ($logo) {
            $this->professionalImage->removeImage($oldImage);
        }

        return $professional;
    }
}
