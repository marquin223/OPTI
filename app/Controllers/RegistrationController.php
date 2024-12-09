<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Login;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class RegistrationController extends Controller
{
    public function showRegister(): void
    {
        include '../app/views/home/register.phtml';
    }

    public function register(Request $request): void
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $phone = $request->input('phone');

        if (!$name || !$email || !$password) {
            FlashMessage::danger('Por favor, preencha todos os campos obrigatórios.');
            $this->redirectTo('/register');
            return;
        }

        $user = new User([
            'name' => $name,
            'phone' => $phone,
        ]);
        $user->save();

        $login = new Login([
            'user_id' => $user->id,
            'email' => $email,
            'password' => $password,
        ]);
        $login->save();

        FlashMessage::success('Registro concluído com sucesso. Faça login.');
        $this->redirectTo('/');
    }
}
