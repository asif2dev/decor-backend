<?php


namespace App\Services;

use App\Services\Images\ImageHandlerInterface;
use App\Services\Images\ProfessionalImage;
use App\Services\Images\ProjectImage;
use App\Services\Images\UserImage;
use Illuminate\Contracts\Foundation\Application;

class ImageHandler
{
    public function __construct(private Application $application)
    {
    }

    public function professional(): ImageHandlerInterface
    {
        return $this->application->make(ProfessionalImage::class);
    }

    public function project(): ImageHandlerInterface
    {
        return $this->application->make(ProjectImage::class);
    }

    public function user(): ImageHandlerInterface
    {
        return $this->application->make(UserImage::class);
    }
}
