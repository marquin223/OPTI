<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use App\Models\Ticket;
use App\Models\Status;
use App\Models\User;
use App\Models\Admin;

class TicketController
{
    public function index()
    {
        $tickets = Ticket::all();
        return Response::view('tickets/index', compact('tickets'));
    }

    public function create()
    {
        return Response::view('tickets/create');
    }

    public function store(Request $request)
    {
        $ticket = new Ticket();
        $ticket->user_id = $request->input('user_id');
        $ticket->status_id = Status::OPEN;
        $ticket->priority_id = $request->input('priority_id');
        $ticket->title = $request->input('title');
        $ticket->description = $request->input('description');
        $ticket->created_date = date('Y-m-d');

        if ($ticket->save()) {
            return Response::redirect('/tickets');
        }

        return Response::view('tickets/create', ['errors' => $ticket->errors()]);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);
        return Response::view('tickets/show', compact('ticket'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        $status = $request->input('status');

        if ($status === 'in_progress') {
            $ticket->markInProgress();
        } elseif ($status === 'resolved') {
            $ticket->markResolved();
        } elseif ($status === 'open') {
            $ticket->reopen();
        }

        return Response::redirect('/tickets/' . $id);
    }
}
