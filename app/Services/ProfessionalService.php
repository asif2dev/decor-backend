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

    /** @throws Throwable */
    public function create(User $user, array $data, UploadedFile $logo): Professional
    {
       try {
           DB::beginTransaction();

           logger()->info('prof data', ['data' => $data]);

           $data['uid'] = (int)(time() . rand(100, 999));
           $data['slug'] = Str::arSlug($data['companyName']);
           $logoPath = $this->professionalImage->uploadImage($logo);

           $data['logo'] = $logoPath;
           $data['offer_execution'] = false;

           $professional = $this->professionalRepository->create($data);
           $professional->categories()->sync($data['categories']);

           $professional->users()->attach($user->id);

           DB::commit();

           return $professional;
       } catch (\Throwable $exception) {
           DB::rollBack();
           throw $exception;
       }
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

    /** @throws Throwable */
    public function update(Professional $professional, array $data, ?UploadedFile $logo = null): Professional
    {
        try {
            DB::beginTransaction();

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
            DB::commit();

            return $professional;
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
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
                    'slug' => Str::arSlug($image['title']) . '-' . $image['id'],
                    'space_id' => empty($image['space_id']) ? null : $image['space_id'],
                    'design_type_id' => empty($image['design_type_id']) ? null : $image['design_type_id'],
                    'description' => $image['description'],
                    'palette' => $image['palette'],
                ]);
        }

        return true;
    }
}
