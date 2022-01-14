<?php
// Login
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
// Report
use App\Http\Controllers\ReportsController;
// Master
use App\Http\Controllers\MstCompaniesController;
use App\Http\Controllers\MstDeptsController;
use App\Http\Controllers\MstGroupsController;
use App\Http\Controllers\UsersController;
// Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login');

Route::middleware(['auth:web', 'verified'])->get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:web')
                ->name('logout');

// Report
Route::resource('report', ReportsController::class)->middleware('auth:web');
Route::post('/report/search', [ReportsController::class, 'search'])->middleware('auth:web')->name('report.search');
// Master
Route::resource('mst_company', MstCompaniesController::class)->middleware('auth:web');
Route::resource('mst_dept', MstDeptsController::class)->middleware('auth:web');
Route::resource('mst_group', MstGroupsController::class)->middleware('auth:web');
Route::get('/mst_user/trash', [UsersController::class, 'trash'])->middleware('auth:web')->name('mst_user.trash');
Route::patch('/mst_user/{id}/restore', [UsersController::class, 'restore'])->middleware('auth:web')->name('mst_user.restore');
Route::delete('/mst_user/force_delete', [UsersController::class, 'forceDelete'])->name('mst_user.force_delete');
Route::resource('mst_user', UsersController::class)->middleware('auth:web');
Route::post('/get_dept', [UsersController::class, 'getDept'])->middleware('auth:web')->name('mst_user.dept');

