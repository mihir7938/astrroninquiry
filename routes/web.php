<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

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

Route::get('/', [AuthController::class, 'getLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('authenticate');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['prefix' => 'password'], function () {
    Route::get('/forget', [AuthController::class, 'forgetPassword'])->name('forget_password');
    Route::post('/reset', [AuthController::class, 'resetPassword'])->name('check_password_reset');
    Route::get('/reset/{token}', [AuthController::class, 'getChangePassword'])->name('reset_password_link');
    Route::post('/reset/new/{token}', [AuthController::class, 'postChangePassword'])->name('change_password');
});

Route::group(['prefix' => 'office', 'middleware' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/cities', [AdminController::class, 'cities'])->name('admin.cities');
    Route::get('/cities/add', [AdminController::class, 'addCity'])->name('admin.cities.add');
    Route::post('/cities/save', [AdminController::class, 'saveCity'])->name('admin.cities.add.save');
    Route::get('/cities/edit/{id}', [AdminController::class, 'editCity'])->name('admin.cities.edit');
    Route::post('/cities/update', [AdminController::class, 'updateCity'])->name('admin.cities.update.save');
    Route::get('/cities/delete/{id}', [AdminController::class, 'deleteCity'])->name('admin.cities.delete');
    Route::get('/business', [AdminController::class, 'business'])->name('admin.business');
    Route::get('/business/add', [AdminController::class, 'addBusiness'])->name('admin.business.add');
    Route::post('/business/save', [AdminController::class, 'saveBusiness'])->name('admin.business.add.save');
    Route::get('/business/edit/{id}', [AdminController::class, 'editBusiness'])->name('admin.business.edit');
    Route::post('/business/update', [AdminController::class, 'updateBusiness'])->name('admin.business.update.save');
    Route::get('/business/delete/{id}', [AdminController::class, 'deleteBusiness'])->name('admin.business.delete');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/add', [AdminController::class, 'addProduct'])->name('admin.products.add');
    Route::post('/products/save', [AdminController::class, 'saveProduct'])->name('admin.products.add.save');
    Route::get('/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/update', [AdminController::class, 'updateProduct'])->name('admin.products.update.save');
    Route::get('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::get('/status', [AdminController::class, 'status'])->name('admin.status');
    Route::get('/status/add', [AdminController::class, 'addStatus'])->name('admin.status.add');
    Route::post('/status/save', [AdminController::class, 'saveStatus'])->name('admin.status.add.save');
    Route::get('/status/edit/{id}', [AdminController::class, 'editStatus'])->name('admin.status.edit');
    Route::post('/status/update', [AdminController::class, 'updateStatus'])->name('admin.status.update.save');
    Route::get('/status/delete/{id}', [AdminController::class, 'deleteStatus'])->name('admin.status.delete');
    Route::get('/assign', [AdminController::class, 'assign'])->name('admin.assign');
    Route::get('/assign/add', [AdminController::class, 'addAssign'])->name('admin.assign.add');
    Route::post('/assign/save', [AdminController::class, 'saveAssign'])->name('admin.assign.add.save');
    Route::get('/assign/edit/{id}', [AdminController::class, 'editAssign'])->name('admin.assign.edit');
    Route::post('/assign/update', [AdminController::class, 'updateAssign'])->name('admin.assign.update.save');
    Route::get('/assign/delete/{id}', [AdminController::class, 'deleteAssign'])->name('admin.assign.delete');
    Route::get('/users', [AdminController::class, 'getUsers'])->name('admin.users');
    Route::post('/fetch-users', [AdminController::class, 'fetchUsers'])->name('admin.users.result');
    Route::get('/users/add', [AdminController::class, 'addUser'])->name('admin.users.add');
    Route::post('/users/save', [AdminController::class, 'saveUser'])->name('admin.users.add.save');
    Route::get('/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/users/update', [AdminController::class, 'updateUser'])->name('admin.users.update.save');
    Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/inquiries', [AdminController::class, 'getInquiries'])->name('admin.inquiries');
    Route::post('/fetch-inquiries', [AdminController::class, 'fetchInquiriesByStatus'])->name('admin.inquiries.fetch');
    Route::get('/inquiries/add', [AdminController::class, 'addInquiry'])->name('admin.inquiry.add');
    Route::post('/inquiries/save', [AdminController::class, 'saveInquiry'])->name('admin.inquiry.save');
    Route::get('/inquiries/edit/{id}', [AdminController::class, 'editInquiry'])->name('admin.inquiries.edit');
    Route::post('/inquiries/update', [AdminController::class, 'updateInquiry'])->name('admin.inquiries.update.save');
    Route::get('/inquiries/delete/{id}', [AdminController::class, 'deleteInquiry'])->name('admin.inquiries.delete');
    Route::post('/inquiries/image/delete', [AdminController::class, 'deleteImage'])->name('admin.inquiries.image.delete');
    Route::post('/inquiries/requirements/delete', [AdminController::class, 'deleteReqPDF'])->name('admin.inquiries.requirements.delete');
    Route::post('/inquiries/quotation/delete', [AdminController::class, 'deleteQuoPDF'])->name('admin.inquiries.quotation.delete');
    Route::get('/deleted-inquiries', [AdminController::class, 'deletedInquiries'])->name('admin.deleted.inquiries');
});

Route::group(['prefix' => 'users', 'middleware' => 'user'], function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/inquiries/add', [UserController::class, 'addInquiry'])->name('users.inquiry.add');
    Route::post('/inquiries/save', [UserController::class, 'saveInquiry'])->name('users.inquiry.save');
    Route::get('/inquiries', [UserController::class, 'getInquiries'])->name('users.inquiries');
    Route::post('/fetch-inquiries', [UserController::class, 'fetchInquiriesByStatus'])->name('users.inquiries.fetch');
    Route::get('/inquiries/edit/{id}', [UserController::class, 'editInquiry'])->name('users.inquiries.edit');
    Route::post('/inquiries/update', [UserController::class, 'updateInquiry'])->name('users.inquiries.update.save');
    Route::post('/inquiries/image/delete', [UserController::class, 'deleteImage'])->name('users.inquiries.image.delete');
    Route::post('/inquiries/requirements/delete', [UserController::class, 'deleteReqPDF'])->name('users.inquiries.requirements.delete');
    Route::post('/inquiries/quotation/delete', [UserController::class, 'deleteQuoPDF'])->name('users.inquiries.quotation.delete');
    Route::get('/assign-inquiries', [UserController::class, 'getAssignInquiries'])->name('users.assign.inquiries');
    Route::post('/fetch-assign-inquiries', [UserController::class, 'fetchAssignInquiriesByStatus'])->name('users.assign.inquiries.fetch');
    Route::get('/team', [UserController::class, 'getTeam'])->name('users.team');
});