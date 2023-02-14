<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
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

Route::get('/', [IndexController::class, 'index']);
Route::get('/_/badge/show', [BadgeController::class, 'show']);
Route::get('/_/user', [UserController::class, 'index']);
Route::get('/_/user/new', [UserController::class, 'newuser']);
Route::post('/_/user/new', [UserController::class, 'newuserPost']);
Route::get('/_/user/edit', [UserController::class, 'edit']);
Route::post('/_/user/edit', [UserController::class, 'editPost']);
Route::get('/_/user/slacklogin', [AuthController::class, 'redirectToSlack']);
Route::get('/_/user/slackdone', [AuthController::class, 'handleSlackCallback']);
Route::get('/_/user/githublogin', [AuthController::class, 'redirectToGithub']);
Route::get('/_/user/githubdone', [AuthController::class, 'handleGithubCallback']);
Route::get('/_/user/googlelogin', [AuthController::class, 'redirectToGoogle']);
Route::get('/_/user/googledone', [AuthController::class, 'handleGoogleCallback']);
Route::post('/_/user/setpublic', [UserController::class, 'setpublic']);
Route::get('/_/user/setavatar', [UserController::class, 'setavatar']);
Route::get('/_/user/addid', [UserController::class, 'addid']);
Route::post('/_/user/delete', [UserController::class, 'delete']);
Route::get('/_/user/logout', [AuthController::class, 'logout']);
Route::get('/{user}', [UserController::class, 'show']);

Route::get('/_/api/me', [ApiController::class, 'me']);
