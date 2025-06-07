<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

// v1

Route::prefix('v1')->group(function () {

    Route::post('token',[CredentialController::class,'getToken']);

    Route::middleware('api.key')->group(function () {
        
        Route::post('login',[AuthController::class,'login']);

        Route::post('register',[AuthController::class,'register']);

        Route::middleware('api.user')->group(function () {
           
            Route::post('me',[AuthController::class,'me']);

            Route::prefix('admin')->group(function () {
                
                Route::resource('events/{event}/committees', Admin\EventCommitteeController::class);
                Route::resource('events/{event}/participants', Admin\EventParticipantController::class);
                Route::resource('events/certificates', Admin\EventCertificateController::class);
                Route::get('events/list', [Admin\EventController::class,'list']);
                Route::post('events/{id}/publish', [Admin\EventController::class,'publish']);
                Route::post('events/{id}/send-notification', [Admin\EventController::class,'sendNotification']);
                Route::post('events/{id}/generate-attendance', [Admin\EventController::class,'generateAttendance']);
                Route::resource('events', Admin\EventController::class);
                
            });


        });

        Route::get('event/{slug}', [EventController::class,'show']);
        Route::post('event/attendance', [EventController::class,'attendance']);
        Route::post('event/{slug}', [EventController::class,'store']);
        
    });

});