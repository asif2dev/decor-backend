<?php
namespace App\Repositories;

use App\Models\User;

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

    public function updateUser(User $user, array $data): bool
    {
        $data = $this->convertToSnakeCase($data);

        return $user->update($data);
    }
}
