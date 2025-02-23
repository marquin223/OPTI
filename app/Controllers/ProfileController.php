<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // 🔹 Buscar o usuário pelo ID salvo na sessão
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);


        if (!$user) {
            exit('Usuário não encontrado.');
        }

        // 🔹 Renderiza a view passando os dados do usuário
        $this->render('home/profile', ['user' => $user]);
    }

    public function upload()
{
    header('Content-Type: application/json');

    // Depuração: Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Método não permitido"]);
        exit;
    }

    // Depuração: Verifica se o arquivo foi enviado
    if (!isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Nenhum arquivo enviado"]);
        exit;
    }

    // Configuração do diretório de uploads
    $uploadDir = __DIR__ . '/../../public/assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;

    // Depuração: Verifica se o arquivo foi movido
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        echo json_encode(["success" => true, "message" => "Upload realizado!", "path" => "/assets/uploads/" . $fileName]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erro ao mover o arquivo."]);
    }
}

}
