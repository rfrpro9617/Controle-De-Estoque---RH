<?php

namespace App\Back\Controllers;

use App\Back\Validators\AuthValidator;
use App\Back\Services\AuthService;
use Core\Auth;
use Core\Csrf;
use Core\Controller;

final class AuthController extends Controller
{
  public function __construct(
    private AuthService $service = new AuthService(),
    private AuthValidator $validator = new AuthValidator()
  ) {}

  public function index(): void
  {
    // If the user is already authenticated, redirect to the stock dashboard.
    // Controll do not know how authentication works, so it uses the Auth class to check if the user is authenticated
    if (Auth::check()) {
      header('Location: ' . BASE_URL . '/estoque');
      exit;
    }
    $this->view('auth/login', [
      'title' => 'Entrar | ' . APP_NAME,
      'csrfToken' => Csrf::token(),
    ], 'auth');
  }

  public function registrationForm(): void
  {
    if (Auth::check()) {
      header('Location: ' . BASE_URL . '/estoque');
      exit;
    }

    $this->view('auth/register', [
      'title' => 'Criar conta | ' . APP_NAME,
      'csrfToken' => Csrf::token(),
    ], 'auth');
  }

  public function login(): void
  {
    if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
      $this->showLoginError('Sua sessão expirou. Atualize a página e tente novamente.');
      return;
    }

    $credentials = $this->validator->validateLogin($_POST);
    if ($credentials === null) {
      $this->showLoginError();
      return;
    }

    $user = $this->service->authenticate($credentials['email'], $credentials['password']);
    if ($user === null) {
      $this->showLoginError();
      return;
    }

    Auth::login($user);

    header('Location: ' . BASE_URL . '/estoque');
    exit;
  }

  public function register(): void
  {
    if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
      $this->showRegistrationError('Sua sessão expirou. Atualize a página e tente novamente.');
      return;
    }

    $registration = $this->validator->validateRegistration($_POST);
    if ($registration === null) {
      $this->showRegistrationError(
        'Confira os dados. A senha deve ter ao menos 8 caracteres e no máximo 72 bytes, e a confirmação deve corresponder.'
      );
      return;
    }

    $user = $this->service->register(
      $registration['name'],
      $registration['email'],
      $registration['password']
    );

    if ($user === null) {
      $this->showRegistrationError('Este e-mail já está cadastrado. Entre ou use outro endereço.');
      return;
    }

    Auth::login($user);

    header('Location: ' . BASE_URL . '/estoque');
    exit;
  }

  public function logout(): void
  {
    if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
      http_response_code(403);
      echo 'A solicitação expirou. Atualize a página e tente novamente.';
      return;
    }

    Auth::logout();

    header('Location: ' . BASE_URL . '/');
    exit;
  }

  private function showLoginError(
    string $message = 'E-mail ou senha inválidos. Confira seus dados e tente novamente.'
  ): void {
    $this->view(
      'auth/login',
      [
        'title' => 'Entrar | ' . APP_NAME,
        'feedback' => $message,
        'feedbackTitle' => 'Falha no login',
        'feedbackType' => 'error',
        'csrfToken' => Csrf::token(),
      ],
      'auth'
    );
  }

  private function showRegistrationError(string $message): void
  {
    $this->view(
      'auth/register',
      [
        'title' => 'Criar conta | ' . APP_NAME,
        'feedback' => $message,
        'feedbackTitle' => 'Cadastro não concluído',
        'feedbackType' => 'error',
        'csrfToken' => Csrf::token(),
        'formName' => is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '',
        'formEmail' => is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '',
      ],
      'auth'
    );
  }
}
