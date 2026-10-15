<?php

namespace Core;

final class Auth
{
  private static ?UserProvider $userProvider = null;
  // User to implement caching and avoid multiple calls to the UserProvider
  // This property is used to store the authenticated user object after it has been retrieved from the UserProvider.
  private static ?object $user = null;
  // Flag to indicate if the user has already been resolved to avoid multiple calls to the UserProvider
  private static bool $userResolved = false;

  public static function setUserProvider(UserProvider $userProvider): void
  {
    self::$userProvider = $userProvider;
  }

  public static function check(): bool
  {
    return isset($_SESSION['auth']['user_id']);
  }

  public static function user(): ?object
  {
    if (self::$userResolved) {
      return self::$user;
    }

    self::$userResolved = true;

    if (!self::check()) {
      return null;
    }

    if (self::$userProvider === null) {
      // TODO: validar como a interface trata a ausência de um UserProvider.
      throw new \LogicException(
        'O UserProvider não foi configurado.'
      );
    }

    // Auth -> userProvider -> findById (do not know findById is implemented in UserRepository, but it should be)
    self::$user = self::$userProvider->findById(
      (int) $_SESSION['auth']['user_id']
    );

    return self::$user;
  }

  public static function login(object $user): void
  {
    // Avoid session fixation attacks by regenerating the session ID upon login
    if (!session_regenerate_id(true)) {
      // TODO: validar como a interface trata a ausência de um UserProvider.
      throw new \RuntimeException(
        'Não foi possível renovar a sessão após a autenticação.'
      );
    }

    $_SESSION['auth'] = [
      'user_id' => $user->id,
    ];

    self::$user = null;
    self::$userResolved = false;
  }

  public static function logout(): void
  {
    self::$user = null;
    self::$userResolved = false;

    $_SESSION = [];

    if (session_status() === PHP_SESSION_ACTIVE && !session_regenerate_id(true)) {
      throw new \RuntimeException(
        'Não foi possível invalidar a sessão após o logout.'
      );
    }
  }
}
