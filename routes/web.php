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

/**
 * Register
 * Login
 * ...
 */
Route::get('/register/{secretString}', 'Auth\RegisterController@showRegistrationForm')->name('registerForm');
Route::post('/register/{secretString}', 'Auth\RegisterController@register')->name('register');

Auth::routes([
    'password.request'  => false, 
    'password.email'    => false, 
    'password.reset'    => false, 
    'password.update'   => false,
    'reset'             => false,
    'register'          => false
]);

/**
 * Homepage
 */
Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

/**
 * Invite users
 */
Route::get('/inviteUser', 'InviteUserController@showInviteUserForm')->name('inviteUserForm')->middleware('checkPermission:invite_user');
Route::post('/inviteUser', 'InviteUserController@inviteUser')->name('inviteUser')->middleware('checkPermission:invite_user');

/**
 * Add credential
 */
Route::get('/addCredential', 'AddCredentialController@showAddCredentialForm')->name('addCredentialForm')->middleware('checkPermission:add_credential');
Route::post('/addCredential', 'AddCredentialController@addCredential')->name('addCredential')->middleware('checkPermission:add_credential');

/**
 * Share credential
 */
Route::get('/shareCredential/{credentialId}', 'ShareCredentialController@showShareCredentialForm')->where('credentialId', '[0-9]+')->name('shareCredentialForm')->middleware('checkPermission:share_credential');
Route::post('/shareCredential/{credentialId}', 'ShareCredentialController@shareCredential')->where('credentialId', '[0-9]+')->name('shareCredential')->middleware('checkPermission:share_credential');

/**
 * Edit credential
 */
Route::get('/editCredential/{credentialId}', 'EditCredentialController@showEditCredentialForm')->where('credentialId', '[0-9]+')->name('editCredentialForm')->middleware('checkPermission:edit_credential');
Route::post('/editCredential/{credentialId}', 'EditCredentialController@editCredential')->where('credentialId', '[0-9]+')->name('editCredential')->middleware('checkPermission:edit_credential');

/**
 * Remove credential
 */
Route::get('/removeCredential/{credentialId}', 'RemoveCredentialController@removeCredential')->where('credentialId', '[0-9]+')->name('removeCredential')->middleware('checkPermission:remove_credential');

/**
 * Manage users
 */
Route::get('/manageUsers', 'ManageUserController@showManageUserForm')->name('manageUsersForm')->middleware('checkPermission:manage_user_role');
Route::post('/manageUsers', 'ManageUserController@manageUser')->name('manageUsers')->middleware('checkPermission:manage_user_role');

/**
 * Add role
 */
Route::get('/addRole', 'AddRoleController@showAddRoleForm')->name('addRoleForm')->middleware('checkPermission:permission_manage_role_permission');
Route::post('/addRole', 'AddRoleController@addRole')->name('addRole')->middleware('checkPermission:permission_manage_role_permission');

/**
 * Edit role
 */
Route::get('/listRole', 'EditRoleController@showAllRole')->name('allRole')->middleware('checkPermission:permission_manage_role_permission');
Route::get('/editRole/{role_id}', 'EditRoleController@showEditRoleForm')->where('role_id', '[0-9]+')->name('editRoleForm')->middleware('checkPermission:permission_manage_role_permission');
Route::post('/editRole/{role_id}', 'EditRoleController@editRole')->where('role_id', '[0-9]+')->name('editRole')->middleware('checkPermission:permission_manage_role_permission');