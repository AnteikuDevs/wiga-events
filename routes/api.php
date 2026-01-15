<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

// v1

Route::prefix(env('API_VERSION', 'v1'))->group(function () {

    Route::post('token',[CredentialController::class,'getToken']);

    Route::middleware('api.key')->group(function () {
        
        Route::post('login',[AuthController::class,'login']);

        Route::post('register',[AuthController::class,'register']);

        Route::middleware('api.user')->group(function () {
           
            Route::post('me',[AuthController::class,'me']);

            Route::prefix('portal')->group(function () {
                
                Route::post('events/{event}/participant-verify/{participant_id}', [Portal\EventParticipantController::class,'verify']);
                Route::post('events/{event}/participants/{participant_id}/model', [Portal\EventParticipantController::class,'changeModel']);
                Route::resource('events/{event}/participants', Portal\EventParticipantController::class);
                Route::get('events/certificates/list', [Portal\EventCertificateController::class,'list']);
                Route::post('events/certificates/{id}/set-default', [Portal\EventCertificateController::class,'setDefault']);
                Route::resource('events/certificates', Portal\EventCertificateController::class);
                Route::get('events/list', [Portal\EventController::class,'list']);
                Route::post('events/{id}/publish', [Portal\EventController::class,'publish']);
                Route::post('events/{id}/send-notification', [Portal\EventController::class,'sendNotification']);
                Route::post('events/{id}/generate-attendance', [Portal\EventController::class,'generateAttendance']);
                Route::resource('events', Portal\EventController::class);

                Route::get('category/list', [Portal\CategoryController::class,'list']);

                Route::middleware(['admin.access'])->group(function () {
                    
                    Route::resource('category', Portal\CategoryController::class);
                    Route::post('users/{id}/verify', [Portal\UserController::class, 'verify']);
                    Route::resource('users', Portal\UserController::class);
                    
                });

                
                Route::get('dashboard', [Portal\DashboardController::class,'summary']);

                Route::post('profile', [Portal\ProfileController::class,'update']);
                Route::post('profile/password', [Portal\ProfileController::class,'updatePassword']);
                
            });


        });

        Route::post('event/REG-{code}', [EventController::class,'payment']);

        Route::get('event/{slug}', [EventController::class,'show']);
        Route::post('event/attendance', [EventController::class,'attendance']);
        Route::post('event/{slug}', [EventController::class,'store']);

        Route::get('category-event', [EventController::class,'categoryEvent']);
        Route::get('event', [EventController::class,'index']);
        
    });

});