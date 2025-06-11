<?php


namespace App\Http\Controllers;

use App\Http\Controllers\ComponentJsController;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

$isLocal = app()->environment('local');
$HomeURL = app()->environment('APP_URL');
$AdminURL = app()->environment('ADMIN_APP_URL');

Route::get('js/wiga-config.js', [ComponentJsController::class,'wigaConfig']);
Route::get('js/{hash}.js', [ComponentJsController::class,'index']);

Route::get('file:{id}', [MyStorageController::class,'fileShow'])->name('file.show');

Route::middleware(['user.guest'])->group(function () {

    Route::get('login', function () {
        return view('login',[
            'title' => 'Login',
            'js' => componentJS('login')
        ]);
    })->name('login');
    
    Route::get('register', function () {
        return view('register',[
            'title' => 'register',
            'js' => componentJS('register')
        ]);
    })->name('register');

});

Route::middleware(['user.token','prevent-back'])->group(function () {


    Route::get('logout', function () {
        return redirect('login')->withCookie(Cookie::forget('wigaevents_id'));
    })->name('logout');

    Route::get('redirect', function () {
            
        if(Auth::user())
            return redirect()->route('admin.dashboard');
        else
            return redirect()->route('login');

    })->name('redirect');

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('dashboard', function () {
            return view('admin.dashboard',[
                'title' => 'Dashboard',
                'events' => Event::latest()->limit(3)->get(),
                'js' => componentJS('admin/dashboard'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => route('admin.dashboard')],
                    ['name' => 'Dashboard', 'url' => null]
                ]
            ]);
        })->name('dashboard');
        
        Route::get('profil', function () {
            return view('admin.profil',[
                'title' => 'Profile',
                'events' => Event::latest()->limit(3)->get(),
                'js' => componentJS('admin/profil'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => route('admin.profil')],
                    ['name' => 'Profil', 'url' => null]
                ]
            ]);
        })->name('profil');
        
        Route::get('events', function () {
            return view('admin.events.index',[
                'title' => 'Acara',
                'js' => componentJS('admin/events/index'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => route('admin.events')],
                    ['name' => 'Acara', 'url' => null]
                ]
            ]);
        })->name('events');
        
        Route::get('events/{id}/committees', function () {
            return view('admin.events.committees',[
                'title' => 'Panitia Acara',
                'js' => componentJS('admin/events/committees'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => '#'],
                    ['name' => 'Acara', 'url' => route('admin.events')],
                    ['name' => 'Panitia Acara', 'url' => null]
                ]
            ]);
        })->name('events.committees');
        
        Route::get('events/{id}/participants', function () {
            return view('admin.events.participants',[
                'title' => 'Peserta',
                'js' => componentJS('admin/events/participants'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => '#'],
                    ['name' => 'Acara', 'url' => route('admin.events')],
                    ['name' => 'Peserta', 'url' => null]
                ]
            ]);
        })->name('events.participants');

        Route::get('event-certificates', function () {
            return view('admin.event-certificates.index',[
                'title' => 'Sertifikat Acara',
                'js' => componentJS('admin/event-certificates/index'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => '#'],
                    ['name' => 'Sertifikat Acara', 'url' => null]
                ]
            ]);
        })->name('event-certificates');

    });

});

Route::redirect('', 'login');

Route::get('/attendance_{token}', [EventController::class,'attendance'])->name('event.attendance');
Route::get('/certificate/cert-{attendance_id}', [EventCertificateController::class,'index'])->name('event.certificate');
Route::get('/{slug}', [EventController::class,'index'])->name('event');