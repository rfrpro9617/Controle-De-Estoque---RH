<?php

namespace App\Back\Models;

final class User
{
  public function __construct(
    public readonly int $id,
    public readonly string $name,
    public readonly string $email,
    private readonly ?string $passwordHash = null,
  ) {}

  public function verifiesPassword(string $password): bool
  {
    return $this->passwordHash !== null
      && password_verify($password, $this->passwordHash);
  }

  public function needsPasswordRehash(): bool
  {
    return $this->passwordHash !== null
      && password_needs_rehash($this->passwordHash, self::passwordAlgorithm());
  }

  public static function passwordAlgorithm(): string|int
  {
    return defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
  }
}
