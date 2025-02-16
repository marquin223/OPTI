<?php

use App\Controllers\AuthenticatorController;
use App\Controllers\RegistrationController;
use App\Controllers\AdminController;
use App\Controllers\TicketController;
use Core\Router\Route;

Route::get('/admin/tickets', [TicketController::class, 'adminIndex']);

Route::post('/admin/tickets/open/{id}', [TicketController::class, 'openTicket']);

Route::post('/admin/tickets/close/{id}', [TicketController::class, 'closeTicket']);

Route::get('/tickets', [TicketController::class, 'index']);

Route::get('/tickets/create', [TicketController::class, 'showCreate']);

Route::post('/tickets/{id}/feedback', [TicketController::class, 'storeFeedback']);

Route::post('/tickets/create', [TicketController::class, 'create']);

Route::get('/tickets/{id}', [TicketController::class, 'show']);

Route::post('/tickets/{id}/update-status', [TicketController::class, 'updateStatus']);

Route::get('/', [AuthenticatorController::class, 'showLogin'])->name('login');

Route::post('/index.php', [AuthenticatorController::class, 'authenticate']);

Route::get('/register', [RegistrationController::class, 'showRegister']);

Route::post('/register', [RegistrationController::class, 'register']);

Route::get('/index.php', [AuthenticatorController::class, 'logout']);

Route::get('/user', [AuthenticatorController::class, 'user'])->name('user');

Route::get('/admin', [AuthenticatorController::class, 'admin'])->name('admin');

Route::get('/admin/admins', [AdminController::class, 'index']);

Route::get('/admin/create', [AdminController::class, 'showCreate']);

Route::post('/admin/create', [AdminController::class, 'create']);

Route::get('/admin/edit/{id}', [AdminController::class, 'showEdit']);

Route::post('/admin/edit/{id}', [AdminController::class, 'edit']);

Route::post('/admin/delete/{id}', [AdminController::class, 'delete']);
