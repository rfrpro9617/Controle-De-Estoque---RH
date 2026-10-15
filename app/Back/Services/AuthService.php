<?php

namespace App\Back\Services;

use App\Back\Models\User;
use App\Back\Repositories\UserRepository;

final class AuthService
{
  public function __construct(private UserRepository $repository = new UserRepository()) {}

  public function authenticate(string $email, string $password): ?User
  {
    $user = $this->repository->findByEmail($email);

    if ($user === null || !$user->verifiesPassword($password)) {
      return null;
    }

    if ($user->needsPasswordRehash()) {
      $this->repository->updatePasswordHash(
        $user->id,
        password_hash($password, User::passwordAlgorithm())
      );
    }

    return $user;
  }

  public function register(string $name, string $email, string $password): ?User
  {
    if ($this->repository->findByEmail($email) !== null) {
      return null;
    }

    $passwordHash = password_hash($password, User::passwordAlgorithm());

    try {
      $id = $this->repository->create($name, $email, $passwordHash);
    } catch (\PDOException $exception) {
      $isDuplicateEmail = $exception->getCode() === '23000'
        && (int) ($exception->errorInfo[1] ?? 0) === 1062;

      if (!$isDuplicateEmail) {
        throw $exception;
      }

      return null;
    }

    return new User($id, $name, $email);
  }
}
