<?php

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
    return view('gobang');
});

Route::get('log/{v}/{x}/{y}/{i}', function ($v, $x, $y, $i) {
   return '666';
});

Route::group(['prefix' => 'data'], function () {
    Route::group(['prefix' => 'gobang'], function () {
        Route::get('create/{player_name}', 'GobangController@CreateChallenge');
        Route::get('match/{player_name}', 'GobangController@Match');

        Route::get('switch/{switch}', 'GobangController@SwitchChess');
        Route::get('switchAns/{id}/{player}', 'GobangController@SwitchAnswer');

        Route::get('success/{id}', 'GobangController@Success');

        Route::group(['prefix' => 'sync'], function () {
            Route::post('player', 'GobangController@PlayerChess');
            Route::post('press', 'GobangController@MatchPlayerChess');
            Route::get('DianziGo', 'GobangController@DianziGoChess');
            Route::get('answer', 'GobangController@PressAnswer');
        });
    });
});