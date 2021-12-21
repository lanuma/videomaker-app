<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

use App\Http\Livewire\TestApi;
use App\Http\Livewire\FormRenderer;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test-api', TestApi::class)->name('test-api');
// Route::get('/get-template', [TestApi::class, 'getTemplate'])->name('get-template');
// Route::get('/create-video', [TestApi::class, 'createVideo'])->name('create-video');

Route::get('/', FormRenderer::class)->name('form');
Route::post('create-video', [FormRenderer::class, 'createVideo'])->name('form.create');

Route::get('/youtube', [App\Http\Controllers\YoutubeController::class, 'index'])->name('youtube');
Route::post('/youtube', [App\Http\Controllers\YoutubeController::class, 'submit'])->name('youtube.submit');
Route::get('/youtube/login', [App\Http\Controllers\YoutubeController::class, 'login'])->name('youtube.login');


Route::get('/youtube-v2', [App\Http\Controllers\Youtubev2Controller::class, 'index'])->name('youtube-v2');
// Route::post('/youtube-v2', [App\Http\Controllers\Youtubev2Controller::class, 'submit'])->name('youtube.submit');


// Route::group(['as' => 'project.', 'prefix' => 'project'], function () {
//     Route::get('/', [ProjectController::class, 'index']);
//     Route::get('/{project_id}', [ProjectController::class, 'show']);
// });
