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

Route::get( '/', function () 
{
    return redirect( '/contacts' );
} );

// Contacts List View
Route::get( '/contacts', 'PagesController@contactsList' )->name( 'contacts' );

// When clicking on Pagination
Route::get( '/fetch_data_users', 'PagesController@fetch_data_users' )->name( 'fetch_data_users' );

// Search User Functionality
Route::get( '/search_data', 'PagesController@search_data' )->name( 'search_data' );

// Contacts Admin View
Route::get( '/admin', 'PagesController@contactsAdmin' )->name( 'admin' );

// Create User
Route::post( '/create/user', 'PagesController@create_user' )->name( 'create.user' );

// Update User
Route::get( '/update_user', 'PagesController@update_user' )->name( 'update_user' );

// Delete User
Route::get( '/delete_user', 'PagesController@delete_user' )->name( 'delete_user' );

// Clearing Cache
Route::get( '/clear-cache', function() 
{
    Artisan::call( 'cache:clear' );
    //return "Cache is cleared";
    return redirect( '/contacts' );
} )->name( 'clear.cache' );