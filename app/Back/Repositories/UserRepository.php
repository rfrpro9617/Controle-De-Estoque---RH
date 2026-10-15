<?php

namespace App\Back\Repositories;

use App\Back\Models\User;
use Core\Database;
use Core\UserProvider;

final class UserRepository implements UserProvider
{
  public function findAll(): array
  {
    $stmt = Database::connection()->query(
      'SELECT id, nome, email FROM estoque_usuarios ORDER BY id DESC'
    );
    return array_map(
      fn(array $row) => new User((int) $row['id'], $row['nome'], $row['email']),
      $stmt->fetchAll()
    );
  }

  public function findById(int $id): ?User
  {
    $stmt = Database::connection()->prepare(
      'SELECT id, nome, email
       FROM estoque_usuarios
       WHERE id = :id
       LIMIT 1'
    );

    $stmt->execute(['id' => $id]);

    $row = $stmt->fetch();

    return $row
      ? new User((int) $row['id'], $row['nome'], $row['email'])
      : null;
  }

  public function findByEmail(string $email): ?User
  {
    $stmt = Database::connection()->prepare(
      'SELECT id, nome, email, senha_hash
       FROM estoque_usuarios
       WHERE email = :email
       LIMIT 1'
    );
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();
    return $row
      ? new User(
        (int) $row['id'],
        $row['nome'],
        $row['email'],
        $row['senha_hash']
      )
      : null;
  }

  public function create(string $name, string $email, string $passwordHash): int
  {
    $stmt = Database::connection()->prepare(
      'INSERT INTO estoque_usuarios (nome, email, senha_hash)
       VALUES (:nome, :email, :senha_hash)'
    );
    $stmt->execute([
      'nome' => $name,
      'email' => $email,
      'senha_hash' => $passwordHash,
    ]);

    return (int) Database::connection()->lastInsertId();
  }

  public function updatePasswordHash(int $id, string $passwordHash): void
  {
    $stmt = Database::connection()->prepare(
      'UPDATE estoque_usuarios SET senha_hash = :senha_hash WHERE id = :id'
    );
    $stmt->execute([
      'senha_hash' => $passwordHash,
      'id' => $id,
    ]);
  }
}
