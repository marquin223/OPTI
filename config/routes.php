<?php

use App\Controllers\AuthenticatorController;
use App\Controllers\RegistrationController;
use App\Controllers\AdminController;
use App\Controllers\TicketController;
use Core\Router\Route;


Router::get('/tickets', [TicketController::class, 'index']);

Router::get('/tickets/create', [TicketController::class, 'create']);

Router::post('/tickets/store', [TicketController::class, 'store']);

Router::get('/tickets/{id}', [TicketController::class, 'show']);

Router::post('/tickets/{id}/update-status', [TicketController::class, 'updateStatus']);

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
