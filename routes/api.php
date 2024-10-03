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

Route::post('/save-or-update_note/{id?}', [AuthController::class, 'saveOrUpdateNote']);
Route::get('get_notes', [AuthController::class, 'getNotes']);
Route::delete('delete_notes', [AuthController::class, 'deleteNotes']);
Route::post('save-or-update-task/{id?}', [AuthController::class, 'saveOrUpdateTask']);
Route::get('get_tasks', [AuthController::class, 'getTasks']);
Route::delete('delete_tasks', [AuthController::class, 'deleteTasks']);

Route::post('save-or-update_leads', [AuthController::class, 'saveOrUpdateLeads']);
Route::get('get_leads', [AuthController::class, 'getLeads']);

Route::post('save_calls', [AuthController::class, 'saveCalls']);
Route::post('save-or-update-call', [AuthController::class, 'saveOrUpdateCalls']);
Route::get('get_calls', [AuthController::class, 'getCalls']);
Route::delete('delete_calls', [AuthController::class, 'deleteCalls']);

Route::post('save-or-update_contacts', [AuthController::class, 'saveOrUpdateContacts']);
Route::get('get_contacts', [AuthController::class, 'getContacts']);
Route::get('search_customer', [AuthController::class, 'searchCustomer']);
Route::post('save-or-update_customers',[AuthController::class,'saveOrUpdateCustomers']);
Route::get('get_customers', [AuthController::class, 'getCustomers']);
Route::post('save-or-update_opportunity',[AuthController::class,'saveOrUpdateOpportunity']);
Route::get('get_opportunity',[AuthController::class,'getOpportunity']);
Route::get('get_company_id', [AuthController::class, 'getCompanyid']);



// Route::post('save_data', [TestControllr::class, 'saveData']);
// Route::get('show_data', [TestControllr::class, 'showData']);
// Route::post('update_data/{id}', [TestControllr::class, 'updateData']);
