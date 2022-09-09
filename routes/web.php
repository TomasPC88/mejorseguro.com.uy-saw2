<?php
use Illuminate\Mail\Markdown;

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

//Ruta para testeo, probar como otra solucion Laravel Tinker

Route::get('test', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('page.components.mails.suscription_canceled');
});


// Encerramos todas las rutas que van dentro de app/Http/Controllers/Page
Route::middleware('locale')->namespace('Page')->as('page.')->group(function () {

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/contacto', 'ContactController@index')->name('contacto');
    Route::post('/contacto/enviar', 'ContactController@send')->name('contacto.send');

    Route::post('suscribir', 'SuscriptionController@suscribe')->name('suscribe');
    Route::get('anular-suscripcion', 'SuscriptionController@unsuscribe')->name('unsuscribe');


    Route::namespace('Auth')->as('auth.')->group(function () {
        //Authentication Urls
        Route::get('login', 'AuthController@login')->name('login');
        Route::post('signin', 'AuthController@signin')->name('signin');
        Route::get('logout', 'AuthController@logout')->name('logout');
        Route::get('perfil', 'AuthController@profile')->name('profile');
        Route::post('perfil/guardar', 'AuthController@saveProfile')->name('profile.save');

        Route::get('registro', 'RegisterController@register')->name('register');
        Route::post('registro/guardar', 'RegisterController@signup')->name('signup');
        Route::get('registro/confirmar', 'RegisterController@confirm')->name('register.confirm');
        Route::get('registro/completo', 'RegisterController@complete')->name('register.complete');

        Route::get('password/recuperar', 'ForgotPasswordController@forgotPassword')->name('forgot-password');
        Route::post('password/solicitud', 'ForgotPasswordController@recoverPassword')->name('forgot-password.recover');
        Route::get('password/solicitud/nuevo-password', 'ForgotPasswordController@askForNewPassword')->name('forgot-password.ask-password');
        Route::post('password/nuevo-password/guardar', 'ForgotPasswordController@saveNewPassword')->name('forgot-password.new-password.save');
        Route::get('password/nuevo-password/completo', 'ForgotPasswordController@recoverSuccess')->name('forgot-password.new-password.complete');
    });

    Route::middleware('only-ajax')->prefix('api')->group(function () {
        //APIRest Urls
    });

    Route::get('lang/{locale}', 'HomeController@changeLocale')->where('locale', 'uk|es|pt')->name('page.change.locale');
});

Route::namespace('Admin')->prefix('admin')->as('admin.')->group(function () {
    // Login -------------------------------------------------------------------------------------------------
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('login.submit');
    Route::get('/permissions/{guard}', 'RoleController@permissions')->name('roles.permissions');

    Route::get('/', 'DashboardController@index')->name('dashboard.index');

    /*--- Sections Resources ---*/
    Route::resource('administradores', 'AdministradoresController');
    Route::resource('portadas', 'PortadaController');
    Route::resource('roles', 'RoleController');
    Route::resource('newsletters', 'NewsletterController')->only('index', 'edit', 'update', 'destroy');
    Route::get('newsletters/csv', 'NewsletterController@csv')->name('newsletters.csv');
    Route::resource('configuracion', 'ConfiguracionController')->only('index', 'update');
    /*--- End of Sections Resources ---*/

    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('logout');

    // Password reset routes ---------------------------------------------------------------------------------
    Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('password.reset');
});
