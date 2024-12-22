<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Login;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class AdminController extends Controller
{
    public function index(): void
    {
        $admins = Admin::all();
        $adminsWithLogin = [];

        foreach ($admins as $admin) {
            $login = Login::where(['admin_id' => $admin->id]);

            if (!empty($login)) {
                $adminsWithLogin[] = [
                'admin' => $admin,
                'login' => $login[0]
                ];
            } else {
                $adminsWithLogin[] = [
                'admin' => $admin,
                'login' => null
                ];
            }
        }

        include '../app/views/admin/admins.phtml';
    }

    public function showCreate(Request $request): void
    {
        $this->render('admin/create');
    }

    public function create(Request $request): void
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $date_birth = $request->input('date_birth');
        $phone = $request->input('phone');

        if (!$name || !$email || !$password) {
            FlashMessage::danger('Por favor, preencha todos os campos obrigatórios.');
            $this->redirectTo('/admin/create');
            return;
        }

        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($emailPattern, $email)) {
            FlashMessage::danger('Endereço de email inválido.');
            $this->redirectTo("/admin/create");
            return;
        }

        $birthDate = new \DateTime($date_birth);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        if ($age < 18) {
            FlashMessage::danger('O administrador deve ter pelo menos 18 anos.');
            $this->redirectTo("/admin/create");
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_birth) || !strtotime($date_birth)) {
            FlashMessage::danger('Data de nascimento inválida. Use o formato YYYY-MM-DD.');
            $this->redirectTo("/admin/create");
            return;
        }

        $phonePattern = '/^\(\d{2}\)\d{5}-\d{4}$/';
        if ($phone && !preg_match($phonePattern, $phone)) {
            FlashMessage::danger('Número de telefone inválido. Use o formato (42)99999-9999.');
            $this->redirectTo('/admin/create');
            return;
        }

        $admin = new Admin([
        'name' => $name,
        'date_birth' => $date_birth,
        'phone' => $phone,
        ]);

        if (!$admin->save()) {
            FlashMessage::danger('Erro ao salvar administrador.');
            $this->redirectTo('/admin/create');
            return;
        }

        $adminId = $admin->id;

        $login = new Login([
        'admin_id' => $adminId,
        'email' => $email,
        'password' => $password,
        ]);

        if (!$login->save()) {
            FlashMessage::danger('Erro ao salvar login associado.');
            $this->redirectTo('/admin/create');
            return;
        }

        FlashMessage::success('Administrador criado com sucesso.');
        $this->redirectTo('/admin/admins');
    }


    public function showEdit(Request $request): void
    {
        $id = $request->getParam('id');

        if (!$id || !is_numeric($id)) {
            FlashMessage::danger('ID inválido.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $id = (int) $id;

        $admin = Admin::findById($id);

        if (!$admin) {
            FlashMessage::danger('Administrador não encontrado.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $login = Login::findByAdminId($id);

        $this->render('admin/edit', [
        'admin' => $admin,
        'login' => $login,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = $request->getParam('id');

        if (!$id || !is_numeric($id)) {
            FlashMessage::danger('ID inválido.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $id = (int) $id;

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $date_birth = $request->input('date_birth');
        $phone = $request->input('phone');

        if (!$name || !$email || !$password) {
            FlashMessage::danger('Por favor, preencha todos os campos obrigatórios.');
            $this->redirectTo("/admin/edit/{$id}");
            return;
        }

        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($emailPattern, $email)) {
            FlashMessage::danger('Endereço de email inválido.');
            $this->redirectTo("/admin/edit/$id");
            return;
        }

        $birthDate = new \DateTime($date_birth);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        if ($age < 18) {
            FlashMessage::danger('O administrador deve ter pelo menos 18 anos.');
            $this->redirectTo("/admin/edit/{$id}");
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_birth) || !strtotime($date_birth)) {
            FlashMessage::danger('Data de nascimento inválida. Use o formato YYYY-MM-DD.');
            $this->redirectTo("/admin/edit/{$id}");
            return;
        }

        $phonePattern = '/^\(\d{2}\)\d{5}-\d{4}$/';
        if ($phone && !preg_match($phonePattern, $phone)) {
            FlashMessage::danger('Número de telefone inválido. Use o formato (42)99999-9999.');
            $this->redirectTo("/admin/edit/{$id}");
            return;
        }

        $admin = Admin::findById($id);
        if (!$admin) {
            FlashMessage::danger('Administrador não encontrado.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $updatedAdmin = $admin->update([
        'name' => $name,
        'date_birth' => $date_birth,
        'phone' => $phone,
        ]);

        if (!$updatedAdmin) {
            FlashMessage::danger('Erro ao atualizar administrador.');
            $this->redirectTo("/admin/edit/{$id}");
            return;
        }

        $login = Login::findByAdminId($id);
        if (!$login) {
            FlashMessage::danger('Dados de login não encontrados para este administrador.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $updatedLogin = $login->update([
        'email' => $email,
        'password' => $password,
        ]);

        if (!$updatedLogin) {
            FlashMessage::danger('Erro ao atualizar os dados de login.');
            $this->redirectTo("/admin/edit/{$id}");
            return;
        }

        FlashMessage::success('Administrador atualizado com sucesso.');
        $this->redirectTo('/admin/admins');
    }

    public function delete(Request $request): void
    {
        $id = $request->getParam('id');

        if (!$id || !is_numeric($id)) {
            FlashMessage::danger('ID inválido.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $id = (int) $id;

        $admin = Admin::findById($id);

        if (!$admin) {
            FlashMessage::danger('Administrador não encontrado.');
            $this->redirectTo('/admin/admins');
            return;
        }

        $login = Login::findByAdminId($id);

        if ($login && !$login->destroy()) {
            FlashMessage::danger('Erro ao excluir o login associado.');
            $this->redirectTo('/admin/admins');
            return;
        }

        if (!$admin->destroy()) {
            FlashMessage::danger('Erro ao excluir o administrador.');
            $this->redirectTo('/admin/admins');
            return;
        }


        FlashMessage::success('Administrador excluído com sucesso');
        $this->redirectTo('/admin/admins');
    }
}
