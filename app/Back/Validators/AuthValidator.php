<?php

namespace App\Back\Validators;

final class AuthValidator
{
  /**
   * @return array{email: string, password: string}|null
   */
  public function validateLogin(array $input): ?array
  {
    $emailInput = $input['email'] ?? null;
    $password = $input['password'] ?? null;

    if (!is_string($emailInput) || !is_string($password) || $password === '') {
      return null;
    }

    $email = filter_var(trim($emailInput), FILTER_VALIDATE_EMAIL);
    if ($email === false || strlen($email) > 255) {
      return null;
    }

    return [
      'email' => $email,
      'password' => $password,
    ];
  }

  /**
   * @return array{name: string, email: string, password: string}|null
   */
  public function validateRegistration(array $input): ?array
  {
    $name = $input['name'] ?? null;
    $emailInput = $input['email'] ?? null;
    $password = $input['password'] ?? null;
    $passwordConfirmation = $input['password_confirmation'] ?? null;

    if (
      !is_string($name)
      || !is_string($emailInput)
      || !is_string($password)
      || !is_string($passwordConfirmation)
    ) {
      return null;
    }

    $name = trim($name);
    $email = filter_var(trim($emailInput), FILTER_VALIDATE_EMAIL);
    $passwordLength = mb_strlen($password, 'UTF-8');

    if (
      $name === ''
      || mb_strlen($name, 'UTF-8') > 255
      || $email === false
      || strlen($email) > 255
      || $passwordLength < 8
      || strlen($password) > 72
      || $password !== $passwordConfirmation
    ) {
      return null;
    }

    return [
      'name' => $name,
      'email' => $email,
      'password' => $password,
    ];
  }
}
