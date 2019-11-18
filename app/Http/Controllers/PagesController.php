<?php

namespace App\Http\Controllers;

use Session;
use App\CreateUser;
use Illuminate\Http\Request;
use App\Http\Controllers\DataController;

class PagesController extends Controller
{
    public function contactsList()
    {
        $heading = 'Contacts';
        $user_search = '';
        
        $is_admin = false;

        $data = new DataController();

        $users = $data->fetch_users();

        return view( 'pages.contacts', compact( 'heading', 'users', 'user_search', 'is_admin' ) );
    }

    public function contactsAdmin()
    {
        $heading = 'Contacts Admin';
        $user_search = '';
        
        $is_admin = true;

        $data = new DataController();

        $users = $data->fetch_users();

        return view( 'pages.contactsAdmin', compact( 'heading', 'users', 'user_search', 'is_admin' ) );
    }

    function fetch_data_users( Request $request )
    {  
        $user_search = '';
        
        if( app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'admin' )
            $is_admin = true;
        else $is_admin = false;

        if( $request->ajax() )
        {
            $data = new DataController();
            $users = $data->fetch_users();

            return view( 'pages.part.contactsResults', compact( 'users', 'user_search', 'is_admin' ) )->render();
        }
    }

    function search_data( Request $request )
    {     
        $user_search = '';
        
        if( app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'admin' )
            $is_admin = true;
        else $is_admin = false;
        
        if( $request->ajax() )
        {
            if( $request->user_search )
                $user_search = $request->user_search;

            $data = new DataController();
            $users = $data->fetch_users( $user_search );

            return view( 'pages.part.contactsResults', compact( 'users', 'user_search', 'is_admin' ) )->render();
        }
    }

    // Create New User
    public function create_user( Request $request )
    {
        $user = new CreateUser;     
        
        $user_arr[ 'name' ] = $request->get( 'name' );
        $user_arr[ 'surname' ] = $request->get( 'surname' );
        $user_arr[ 'contacts' ] = $request->get( 'contacts' );

        if( null !== $request->get( 'name' ) && null !== $request->get( 'surname' ) && null !== $request->get( 'contacts' ) )
            $results = $user->create_user( $user_arr );
        else return redirect( 'admin' )->withErrors( 'User was not added! All fields need to be filled in.' );
        
        if( isset( $results[ 'user' ] ) && ! $results[ 'user' ] )
        {
            return redirect( 'admin' )->withErrors( 'User was not added!' );
        }
        else if( isset( $results[ 'user' ] ) && $results[ 'user' ] )
        {
            Session::flash( 'message', 'User added Successfully!' );

            return redirect( 'admin' );
        }
        else return redirect( 'admin' )->withErrors( 'User was not added!' );
    }

    // Update New User
    public function update_user( Request $request )
    {
        if( $request->ajax() )
        {
            $user_arr[ 'updateId' ] = $request->get( 'updateId' );
            $user_arr[ 'name' ] = $request->get( 'name' );
            $user_arr[ 'surname' ] = $request->get( 'surname' );
            $user_arr[ 'contacts' ][ 'email' ] = $request->get( 'email' );
            $user_arr[ 'contacts' ][ 'alternative_email' ] = $request->get( 'alternative_email' );
            $user_arr[ 'contacts' ][ 'phone' ] = $request->get( 'phone' );
            $user_arr[ 'contacts' ][ 'alternative_phone' ] = $request->get( 'alternative_phone' );

            $data = new DataController();
            
            $results = $data->update_user( $user_arr );
            
            echo json_encode( $results );
        }
    }

    // Delete User
    function delete_user( Request $request )
    {
        if( $request->ajax() )
        {
            $data = new DataController();
            
            $results = $data->delete_user( $request->deleteId );
            
            echo json_encode( $results );
        }
    }
}
