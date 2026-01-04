<?php


namespace App\Http\Controllers;

use App\Http\Controllers\ComponentJsController;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

$isLocal = app()->environment('local');
$HomeURL = app()->environment('APP_URL');
$appURL = app()->environment('app_APP_URL');

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
            return redirect()->route('portal.dashboard');
        else
            return redirect()->route('login');

    })->name('redirect');

    Route::prefix('portal')->name('portal.')->group(function () {

        Route::get('dashboard', function () {
            return view('portal.dashboard',[
                'title' => 'Dashboard',
                'events' => Event::latest()->limit(3)->get(),
                'js' => componentJS('portal/dashboard'),
                'breadcrumb' => [
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Dashboard', 'url' => null]
                ]
            ]);
        })->name('dashboard');
        
        Route::get('profile', function () {
            return view('portal.profile',[
                'title' => 'Profile',
                'js' => componentJS('portal/profile'),
                'breadcrumb' => [
                    ['name' => 'Dashboard', 'url' => route('portal.dashboard')],
                    ['name' => 'Profil', 'url' => null]
                ]
            ]);
        })->name('profile');
        
        Route::get('events', function () {
            return view('portal.events.index',[
                'title' => 'Acara',
                'js' => componentJS('portal/events/index'),
                'breadcrumb' => [
                    ['name' => 'Dashboard', 'url' => route('portal.dashboard')],
                    ['name' => 'Acara', 'url' => null]
                ]
            ]);
        })->name('events');
        
        Route::get('events/create', function () {
            return view('portal.events.form',[
                'title' => 'Tambah Acara',
                'js' => componentJS('portal/events/create'),
                'breadcrumb' => [
                    ['name' => 'Dashboard', 'url' => route('portal.dashboard')],
                    ['name' => 'Acara', 'url' => route('portal.events')],
                    ['name' => 'Tambah Acara', 'url' => null]
                ]
            ]);
        })->name('events.create');
        
        Route::get('events/{id}', function () {
            return view('portal.events.form',[
                'title' => 'Edit Acara',
                'js' => componentJS('portal/events/edit'),
                'breadcrumb' => [
                    ['name' => 'Dashboard', 'url' => route('portal.dashboard')],
                    ['name' => 'Acara', 'url' => route('portal.events')],
                    ['name' => 'Edit Acara', 'url' => null]
                ]
            ]);
        })->name('events.edit');
        
        Route::post('events/certificates/preview', [EventCertificateController::class,'preview'])->name('events.certificates.preview');

        Route::get('events/{id}/certificates', function ($id) {

            $event = Event::findOrFail($id);

            return view('portal.events.certificates',[
                'title' => 'Template Sertifikat '.($event->name),
                'js' => componentJS('portal/events/certificates'),
                'breadcrumb' => [
                    ['name' => 'Dashboard', 'url' => route('portal.dashboard')],
                    ['name' => 'Acara', 'url' => route('portal.events')],
                    ['name' => 'Template Sertifikat '.($event->name), 'url' => null]
                ]
            ]);
        })->name('events.certificates');

        Route::get('events/{id}/participants', function ($id) {

            Event::findOrFail($id);

            return view('portal.events.participants',[
                'title' => 'Peserta',
                'js' => componentJS('portal/events/participants'),
                'breadcrumb' => [
                    ['name' => 'Dashboard', 'url' => route('portal.dashboard')],
                    ['name' => 'Acara', 'url' => route('portal.events')],
                    ['name' => 'Peserta', 'url' => null]
                ]
            ]);
        })->name('events.participants');

        Route::middleware(['admin.access'])->group(function () {
            
            Route::get('categories', function () {
                return view('portal.categories.index',[
                    'title' => 'Kategori',
                    'js' => componentJS('portal/categories/index'),
                    'breadcrumb' => [
                        ['name' => 'Dashboard', 'url' => route('portal.categories')],
                        ['name' => 'Kategori', 'url' => null]
                    ]
                ]);
            })->name('categories');
            
            Route::get('users', function () {
                return view('portal.users.index',[
                    'title' => 'Kategori',
                    'js' => componentJS('portal/users/index'),
                    'breadcrumb' => [
                        ['name' => 'Dashboard', 'url' => route('portal.users')],
                        ['name' => 'Kategori', 'url' => null]
                    ]
                ]);
            })->name('users');

        });

        
        // Route::get('events/{id}/participants', function () {
        //     return view('portal.events.participants',[
        //         'title' => 'Peserta',
        //         'js' => componentJS('portal/events/participants'),
        //         'breadcrumb' => [
        //             ['name' => 'Beranda', 'url' => '#'],
        //             ['name' => 'Acara', 'url' => route('portal.events')],
        //             ['name' => 'Peserta', 'url' => null]
        //         ]
        //     ]);
        // })->name('events.participants');

        // Route::get('event-certificates', function () {
        //     return view('portal.event-certificates.index',[
        //         'title' => 'Sertifikat Acara',
        //         'js' => componentJS('portal/event-certificates/index'),
        //         'breadcrumb' => [
        //             ['name' => 'Dashboard', 'url' => '#'],
        //             ['name' => 'Sertifikat Acara', 'url' => null]
        //         ]
        //     ]);
        // })->name('event-certificates');

    });

});


Route::middleware(['user.token.verified'])->group(function () {
    
    Route::get('', function () {
        return view('home',[
            'title' => 'home',
            'js' => componentJS('home')
        ]);
    })->name('home');

    Route::get('/_reg_/REG-{code}/certificate', [EventCertificateController::class,'index'])->name('event.certificate');
    Route::get('/_reg_/REG-{code}', [EventController::class,'regCodeGenerate'])->name('event.reg-code.generate');
    Route::get('/attendance_{token}', [EventController::class,'attendance'])->name('event.attendance');
    Route::get('/attendance:{token}', [EventController::class,'showQr'])->name('event.attendance.qr');
    
    Route::get('/event/{slug}', [EventController::class,'show'])->name('event.show');
    Route::get('/event', [EventController::class,'index'])->name('event');
    // Route::get('/certificate/cert-{attendance_id}', [EventCertificateController::class,'index'])->name('event.certificate');

});
