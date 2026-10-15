<?php

namespace Core;

final class AuthGuard implements Middleware
{
  public function handle(): void
  {
    // Check if the user can be retrieved from the database using the UserProvider.
    if (Auth::user() === null) {
      Auth::logout();

      header('Location: ' . BASE_URL . '/login');
      exit;
    }
  }
}
