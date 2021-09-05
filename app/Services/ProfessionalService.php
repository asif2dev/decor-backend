<?php


namespace App\Services;


use App\Models\Professional;
use App\Repositories\ProfessionalRepository;
use Illuminate\Support\Collection;

class ProfessionalService
{
    public function __construct(private ProfessionalRepository $professionalRepository)
    {
    }

    public function create(array $data): Professional
    {
        $data['uid'] = (int) (time() . rand(100, 999));

        return $this->professionalRepository->create($data);
    }

    public function getTopRated(): Collection
    {
        return $this->professionalRepository->getTopRated();
    }
}
