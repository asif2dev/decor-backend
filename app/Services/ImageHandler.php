<?php


namespace App\Services;

use App\Services\Images\ProjectImage;
use App\Services\Images\UserImage;
use Illuminate\Http\UploadedFile;

class ImageHandler
{
    private UserImage $userImage;
    private ProjectImage $itemImage;

    public function __construct(UserImage $userImage, ProjectImage $itemImage)
    {
        $this->userImage = $userImage;
        $this->itemImage = $itemImage;
    }

    public function uploadUserImage(UploadedFile $image): string
    {
        return $this->userImage->uploadImage($image);
    }

    public function uploadProjectImage(UploadedFile $image): string
    {
        return $this->itemImage->uploadImage($image);
    }

    public function removeProjectImages(array $images): void
    {
        foreach ($images as $image) {
            $this->itemImage->removeImage($image);
        }
    }

    public function removeUserImage(?string $image = null): void
    {
        if ($image === null) {
            return;
        }

        $this->userImage->removeImage($image);
    }
}
