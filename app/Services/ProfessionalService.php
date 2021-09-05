<?php


namespace App\Services;


use App\Models\Professional;
use App\Models\User;
use App\Repositories\ProfessionalRepository;
use Illuminate\Support\Collection;

class ProfessionalService
{
    public function __construct(private ProfessionalRepository $professionalRepository)
    {
    }

    public function create(User $user, array $data): Professional
    {
        $data['uid'] = (int) (time() . rand(100, 999));

        $professional = $this->professionalRepository->create($data);
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
}
