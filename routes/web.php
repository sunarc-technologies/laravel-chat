<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Sunarc\LaravelChat\Http\Controller', 'middleware' => ['web', 'auth']], function () {
    Route::view('chat', 'laravel-chat::chat');
    Route::get('userlist', 'MessageController@user_list')->name('user.list');
    Route::get('usermessage/{id}', 'MessageController@user_message')->name('user.message');
    Route::get('deletesinglemessage/{id}', 'MessageController@delete_single_message')->name('user.message.delete.single');
    Route::get('deleteallmessage/{id}', 'MessageController@delete_all_message')->name('user.message.delete.all');

    Route::post('sendmessage', 'MessageController@send_message')->name('user.message.send');
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('typingevent', function ($user) {
    return \Illuminate\Support\Facades\Auth::check();
});

Broadcast::channel('liveuser', function ($user) {
    return $user;
});
