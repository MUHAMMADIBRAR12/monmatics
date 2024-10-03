<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->group(function () {
    // Your protected routes
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('get_users', [AuthController::class, 'getUsers']);

Route::post('save_notes', [AuthController::class, 'saveNotes']);
Route::get('get_notes', [AuthController::class, 'getNotes']);

Route::delete('delete_notes', [AuthController::class, 'deleteNotes']);

Route::post('save_tasks', [AuthController::class, 'saveTasks']);
Route::get('get_tasks', [AuthController::class, 'getTasks']);
Route::post('save_leads', [AuthController::class, 'saveLeads']);
Route::get('get_leads', [AuthController::class, 'getLeads']);
Route::post('save_calls', [AuthController::class, 'saveCalls']);
Route::get('get_calls', [AuthController::class, 'getCalls']);
Route::post('save_contacts', [AuthController::class, 'saveContacts']);
Route::get('get_contacts', [AuthController::class, 'getContacts']);
Route::get('search_customer', [AuthController::class, 'searchCustomer']);
Route::get('get_customers', [AuthController::class, 'getCustomers']);


// Route::post('save_data', [TestControllr::class, 'saveData']);
// Route::get('show_data', [TestControllr::class, 'showData']);
// Route::post('update_data/{id}', [TestControllr::class, 'updateData']);
