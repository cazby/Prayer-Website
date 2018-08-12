<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('groups', 'GroupController', ['except' => ['create', 'edit']]);
Route::resource('groups.invites', 'GroupInviteController', ['except' => ['create', 'edit', 'update']]);

Route::model('invite', App\GroupInvite::class);
