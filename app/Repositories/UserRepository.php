<?php
namespace App\Repositories;

use App\Models\Professional;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    public function createUser(array $data): User
    {
        $data = $this->convertToSnakeCase($data);
        $data['uid'] = uniqid();

        return User::create($data);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getUserByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    public function updateUser(User $user, array $data): bool
    {
        $data = $this->convertToSnakeCase($data);

        return $user->update($data);
    }

    public function toggleFavoriteProfessional(User $user, Professional $professional): void
    {
        $exists = $user->favoriteProfessionals()->where('id', $professional->id)->exists();
        if ($exists) {
            $user->favoriteProfessionals()->detach($professional->id);
        } else {
            $user->favoriteProfessionals()->attach($professional->id);
        }
    }

    public function toggleFavoriteProjectImage(User $user, ProjectImage $projectImage): void
    {
        $exists = $user->favoriteProjectImages()->where('id', $projectImage->id)->exists();
        if ($exists) {
            $user->favoriteProjectImages()->detach($projectImage->id);
        } else {
            $user->favoriteProjectImages()->attach($projectImage->id);
        }
    }

    public function getFavoritesProfessionals(User $user): Collection
    {
        return $user->favoriteProfessionals()->get();
    }

    public function getFavoritesImages(User $user): Collection
    {
        return $user->favoriteProjectImages()->get();
    }
}
