<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false, 'verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function(){
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users
Route::middleware('auth')->prefix('users')->name('users.')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');

    // ------------- Client Routes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/get', [ClientController::class, 'getClients'])->name('clients.get');
    Route::post('/client/create', [ClientController::class, 'create'])->name('client.create');
    Route::get('/client/get', [ClientController::class, 'getClient'])->name('client.get');
    Route::post('/client/update', [ClientController::class, 'updateClient'])->name('client.update');
    Route::post('/client/delete', [ClientController::class, 'deleteClient'])->name('client.delete');

    // ------------- Proposal Routes
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals');
    Route::get('/proposals/get', [ProposalController::class, 'getData'])->name('proposals.get');
    Route::get('/section/get', [ProposalController::class, 'getSection'])->name('section.get');
    Route::post('/section/add', [ProposalController::class, 'addSection'])->name('section.add');
    Route::post('/section/update', [ProposalController::class, 'updateSection'])->name('section.update');
    Route::post('/section/delete', [ProposalController::class, 'deleteSection'])->name('section.delete');
    Route::post('/signature/get', [ProposalController::class, 'getSignature'])->name('signature.get');
    Route::post('/signature/save', [ProposalController::class, 'saveSignature'])->name('signature.save');
    Route::post('/proposal/create', [ProposalController::class, 'create'])->name('proposal.create');
    Route::get('/proposal/show/{slug}', [ProposalController::class, 'showProposal'])->name('proposal.show');
    Route::get('/proposal/load/{slug}', [ProposalController::class, 'loadData'])->name('proposal.load');
    Route::post('/proposal/update', [ProposalController::class, 'updateData'])->name('proposal.update');
    Route::post('/proposal/delete', [ProposalController::class, 'deleteData'])->name('proposal.delete');
    Route::post('/proposal/send', [ProposalController::class, 'sendProposal'])->name('proposal.send');
    Route::post('/proposal/save/temp', [ProposalController::class, 'saveTempProposal'])->name('proposal.save.template');

    // ------------- Template
    Route::get('/templates', [TemplateController::class, 'index'])->name('template');
    Route::post('/templates/get', [TemplateController::class, 'getTemplates'])->name('template.get');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/get', [CategoryController::class, 'getData'])->name('categories.get');
    Route::post('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::get('/category/get', [CategoryController::class, 'getCategory'])->name('category.get');
    Route::post('/category/update', [CategoryController::class, 'updateCategory'])->name('category.update');
    Route::post('/category/delete', [CategoryController::class, 'deleteCategory'])->name('category.delete');
    Route::post('/proposal/duplicate', [TemplateController::class, 'duplicate'])->name('proposal.duplicate');


    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');

    Route::get('export/', [UserController::class, 'export'])->name('export');

    // send email
    Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('send.email');

});

