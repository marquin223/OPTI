<?php

namespace App\Controllers;

use App\Models\Login;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Core\Http\Response;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticatorController extends Controller
{

  public function showLogin()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
      include '../app/views/home/index.phtml';
  }
  public function authenticate(Request $request): void
  {
    $email = $request->input('email');
    $password = $request->input('password');

      $login = Login::first(['email' => $email]);

      if (!$login || $password !== $login->password) {
          FlashMessage::danger('Credenciais inválidas');
          $this->redirectTo('/');
          return;
      }

      if ($login->admin_id) {
          $_SESSION['user_id'] = $login->id;
          FlashMessage::success('Login bem sucedido');
          $this->redirectTo('admin');
      }


      if ($login->user_id) {
        FlashMessage::success('Login bem sucedido');
          $_SESSION['user_id'] = $login->id;
          $this->redirectTo('user');
      }


      FlashMessage::danger('Erro inesperado. Entre em contato com o suporte.');
      $this->redirectTo('/');
  }

    public function logout(): void
    {
        Auth::logout();
        FlashMessage::success('Você foi desconectado!');
        $this->redirectTo('/');
    }
    public function admin()
    {

        return view('admin');

    }
    public function user()
    {

        return view('user');
    }
}
