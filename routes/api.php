<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::post('/login', 'UserAuthController@login');
    Route::post('/forgot-password', 'UserAuthController@sendResetPasswordLink');
    Route::post('/recovery-password', 'UserAuthController@recoveryPassword');
    // Route::post('/register', ["UserAuthController@ 'register']);
    Route::post('/verify-otp', 'OtpController@verifyOTP');

    Route::get('settings', 'SettingController@index');
    Route::post('settings', 'SettingController@update');

    Route::middleware('basic.auth')->group(function () {

    });

    Route::middleware('auth:api')->group(function () {
        Route::get('/user', 'UserAuthController@user');
        Route::get('/logout', 'UserAuthController@logout');
        Route::post('/change-password', 'UserAuthController@changePassword');
        Route::post('/change-first-password', 'UserAuthController@ChangeFirstPassword');
        Route::post('/reset-password', 'UserAuthController@resetPassword');
        Route::post('/user-update', 'UserAuthController@update');
        Route::post('/user-permissions', 'UserAuthController@userPermission');

        Route::post('/user-push-token', 'UserAuthController@addPushToken');
        Route::get('/user-push-token-delete/{id}', 'UserAuthController@deletePushToken');

        Route::post('/upload-file', 'UserAuthController@uploadFile');
        Route::get('/delete-file', 'UserAuthController@deleteFile');

        Route::apiResources([
            'users' => 'UserController',
            'roles' => 'RoleController',
            'profiles' => 'ProfilController',
            'permissions' => 'PermissionController',
            'notifications' => 'NotificationController',
            "couvertures"=>"CouvertureController",
            "affectations"=>"AffectationController",
            "entities"=>"EntiteAdminController",
            "natures"=>"NatureController",
            "sectors"=>"SectorController",
            "structures"=>"StructureController",
            "reformes"=>"ReformeController",
            "objectifs"=>"ObjectifController",
            "resultats"=>"ResultController",
            "suivi-results"=>"SuivreResultController",

        ]);

        Route::get('/logs', 'LogController@index');


        Route::get('/dash', 'DashboardController@getDash');
        Route::get('dash/{role}', 'DashboardController@index');
        Route::post('files', 'DashboardController@storeFile');


        Route::post('send-otp', 'OtpController@sendOTP');

        Route::get('notifications/{id}/state/{state}', 'NotificationController@changeState');
        Route::post('notifications-search', 'NotificationController@search');

         Route::get('couvertures/{id}/state/{state}', 'CouvertureController@changeState');
        Route::post('couvertures-search', 'CouvertureController@search');

        Route::get('affectations/{id}/state/{state}', 'AffectationController@changeState');
        Route::post('affectations-search', 'AffectationController@search');

        Route::get('entities/{id}/state/{state}', 'EntiteAdminController@changeState');
        Route::post('entities-search', 'EntiteAdminController@search');

        Route::get('natures/{id}/state/{state}', 'NatureController@changeStatgeState');
        Route::post('natures-search', 'NatureController@search');

        Route::get('sectors/{id}/state/{state}', 'SectorController@changeStatgeState');
        Route::post('sectors-search', 'SectorController@search');

        Route::get('structures/{id}/state/{state}', 'StructureController@changeStatgeState');
        Route::post('structures-search', 'StructureController@search');

        Route::get('reformes/{id}/state/{state}', 'ReformeController@changeStatgeState');
        Route::post('reformes-search', 'ReformeController@search');

        Route::get('objectifs/{id}/state/{state}', 'objectifController@changeStatgeState');
        Route::post('objectifs-search', 'ObjectifController@search');

        Route::get('resultats/{id}/state/{state}', 'ResultController@changeStatgeState');
        Route::post('resultats-search', 'ResultController@search');

        Route::get('suivi-results/{id}/state/{state}', 'SuivreResultController@changeStatgeState');
        Route::post('suivi-results-search', 'SuivreResultController@search');

        Route::post('roles-search', 'RoleController@search');
        Route::post('permissions-search', 'PermissionController@search');

        Route::get('user-settings', 'UserSettingController@index');
        Route::put('user-settings', 'UserSettingController@update');

        Route::get('users/{id}/state/{state}', 'UserController@changeState');
        Route::post('users-search', 'UserController@search');

        Route::post('/profiles-copy', 'PermissionController@setCopy');

        Route::get('backups', 'BackupController@index');

        Route::get('save-db', 'AuthController@saveDb');

    });
});
