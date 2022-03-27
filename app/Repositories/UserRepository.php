<?php
namespace App\Repositories;

use App\Models\Professional;
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

    public function toggleFavorites(User $user, Professional $professional): void
    {
        $exists = $user->favorites()->where('id', $professional->id)->exists();
        if ($exists) {
            $user->favorites()->detach($professional->id);
        } else {
            $user->favorites()->attach($professional->id);
        }
    }

    public function getFavorites(User $user): Collection
    {
        return $user->favorites;
    }
}
