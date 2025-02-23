<?php

namespace App\Controllers;

use App\Models\Ticket;
use App\Models\Priority;
use App\Models\Status;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class TicketController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            FlashMessage::danger('Erro: Usuário não autenticado.');
            $this->redirectTo('/');
            return;
        }

        $user_id = $_SESSION['user_id'];
        $tickets = Ticket::where(['user_id' => $user_id]);

        $this->render('tickets/index', compact('tickets', 'user_id'));
    }

    public function adminIndex(): void
    {
        $tickets = Ticket::all();
        $this->render('admin/tickets/index', ['tickets' => $tickets]);
    }

    public function showCreate(Request $request): void
    {
        $this->render('tickets/create');
    }

    public function create(Request $request): void
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $admin_id = $request->input('admin_id');
        $user_id = $_SESSION['user_id'];
        $priority_id = (int) $request->input('priority_id');
        $status_id = 1;

        if (!isset($_SESSION['user_id'])) {
            FlashMessage::danger('Erro: Usuário não autenticado.');
            $this->redirectTo('/tickets/create');
            return;
        }

        if (!$title || !$description) {
            FlashMessage::danger('Por favor, preencha todos os campos obrigatórios.');
            $this->redirectTo('/tickets/create');
            return;
        }

        if (empty($priority_id)) {
            FlashMessage::danger('A prioridade é obrigatória.');
            $this->redirectTo('/tickets/create');
            return;
        }

        $ticket = new Ticket([
            'title' => $title,
            'description' => $description,
            'admin_id' => $admin_id ?? null,
            'user_id' => $user_id ?? null,
            'priority_id' => $priority_id,
            'status_id' => (int) $status_id,
            'created_date' => date('Y-m-d H:i:s'),
        ]);


        if (!$ticket->save()) {
            FlashMessage::danger('Erro ao salvar ticket.');
            $this->redirectTo('/tickets/create');
            return;
        }

        FlashMessage::success('Ticket criado com sucesso.');
        $this->redirectTo('/tickets');
    }

    public function show(Request $request): void
    {
        $id = $request->getParam('id');

        if (!$id || !is_numeric($id)) {
            die('ID inválido.');
        }

        $id = (int) $id;

        $ticket = Ticket::findById($id);

        if (!$ticket) {
            die('Ticket não encontrado.');
        }

        require_once __DIR__ . '/../views/tickets/show.phtml';
    }


    public function openTicket(Request $request): void
    {
        $ticketId = $request->getParam('id');
        $adminId = $_SESSION['admin_id'] ?? null;

        if (!$adminId) {
            FlashMessage::danger('Erro: Usuário não autenticado.');
            $this->redirectTo('/admin/tickets');
            return;
        }

        $ticket = Ticket::findById($ticketId);
        if (!$ticket) {
            FlashMessage::danger('Ticket não encontrado.');
            $this->redirectTo('/admin/tickets');
            return;
        }

        $ticket->update([
          'status_id' => 2,
          'admin_id' => $adminId,
        ]);

        FlashMessage::success('Chamado aberto com sucesso.');
        $this->redirectTo('/admin/tickets');
    }


    public function closeTicket(Request $request): void
    {
        $ticketId = $request->getParam('id');

        $ticket = Ticket::findById($ticketId);
        if (!$ticket) {
            FlashMessage::danger('Ticket não encontrado.');
            $this->redirectTo('/admin/tickets');
            return;
        }

        $ticket->update([
        'status_id' => 3,
        'closing_date' => date('Y-m-d H:i:s'),
        ]);

        FlashMessage::success('Chamado finalizado com sucesso.');
        $this->redirectTo('/admin/tickets');
    }
}
