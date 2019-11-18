<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DataController extends Controller
{
    protected $posts_per_page = 5;

    // Fetch Users
    function fetch_users( $search = '' )
    {
        $table_name = 'users';

        $select_raw = DB::raw(
            'id,
            name,
            surname,
            (select user_contacts.contact from user_contacts left join contact_type on contact_type.id = user_contacts.contact_type where contact_type.contact = \'email\' and users.id = user_contacts.user_id ) as email,
            (select user_contacts.contact from user_contacts left join contact_type on contact_type.id = user_contacts.contact_type where contact_type.contact = \'alternative_email\' and users.id = user_contacts.user_id ) as alternative_email,
            (select user_contacts.contact from user_contacts left join contact_type on contact_type.id = user_contacts.contact_type where contact_type.contact = \'phone\' and users.id = user_contacts.user_id ) as phone,
            (select user_contacts.contact from user_contacts left join contact_type on contact_type.id = user_contacts.contact_type where contact_type.contact = \'alternative_phone\' and users.id = user_contacts.user_id ) as alternative_phone'
        );
        
        if( $search != '' )
        {
            $where = [
                [ $table_name . '.name', 'like', '%' . $search . '%' ],
            ];

            $or = [
                [ $table_name . '.surname', 'like', '%' . $search . '%' ],
            ];

            $db_data = DB::table( $table_name )->select( $select_raw )->where( $where )->orWhere( $or )->orderBy( 'name', 'asc' )->distinct( 'id' )->paginate( $this->posts_per_page );
        }
        else
            $db_data = DB::table( $table_name )->select( $select_raw )->orderBy( 'name', 'asc' )->distinct( 'id' )->paginate( $this->posts_per_page );
      
        return $db_data;
    } 

    // Delete User
    function delete_user( $id )
    {    
        $table_name = 'users';
        $table_name_linked = 'user_contacts';

        if( \Schema::hasTable( $table_name ) )
        {
            $db_data_linked = DB::table( $table_name_linked )->where( 'user_id', $id )->delete();

            if( $db_data_linked == 1 )
                $db_data = DB::table( $table_name )->where( 'id', $id )->delete();
            else return false;            

            if( $db_data == 1 )
                return true;    
            else return false;
        }

        return false;
    }

    // Update User
    function update_user( $user )
    {    
        $table_name = 'users';
        $table_name_two = 'user_contacts';

        $updated = $exist = [];

        if( \Schema::hasTable( $table_name ) )
        {
            $exist[ 'name' ] = DB::table( 'users' )->select( 'name' )->where( 'name', $user[ 'name' ] )->get()->first();
            $exist[ 'surname' ] = DB::table( 'users' )->select( 'surname' )->where( 'surname', $user[ 'surname' ] )->get()->first();
        
            if( $exist[ 'name' ] != null )
            {
                $updated[ $table_name ][ 'name' ] = false;
            }
            else
            {
                $db_data = DB::table( $table_name )->where( 'id', $user[ 'updateId' ] )->update( [ 'name' => $user[ 'name' ] ] );        
                
                if( $db_data == 1 )
                    $updated[ $table_name ][ 'name' ] = true;    
                else $updated[ $table_name ][ 'name' ] = false;
            }

            if( $exist[ 'surname' ] != null )
            {
                $updated[ $table_name ][ 'surname' ] = false;
            }
            else
            {
                $db_data = DB::table( $table_name )->where( 'id', $user[ 'updateId' ] )->update( [ 'surname' => $user[ 'surname' ] ] );        

                if( $db_data == 1 )
                    $updated[ $table_name ][ 'surname' ] = true;    
                else $updated[ $table_name ][ 'surname' ] = false;
            }
        }

        if( \Schema::hasTable( $table_name_two ) )
        {
            $contact_type_arr = DB::table( 'contact_type' )->select( [ 'id', 'contact' ] )->orderBy( 'id', 'asc' )->get()->keyBy( 'contact' )->toArray();

            foreach( $user[ 'contacts' ] as $key => $contact )
            {

                $exist[ 'contacts' ][ $key ] = DB::table( $table_name_two )->select( 'contact' )->where( 'contact', $contact )->get()->first();

                if( $exist[ 'contacts' ][ $key ] != null )
                {
                    $updated[ $table_name_two ][ $key ] = false;
                }
                else
                {
                    $where = [
                        [ 'user_id', '=', $user[ 'updateId' ] ],
                        [ 'contact_type', '=', $contact_type_arr[ $key ]->id ],
                    ];

                    if( $key == 'alternative_email' || $key == 'alternative_phone' )
                        $alternative_contact = DB::table( $table_name_two )->select( 'contact' )->where( $where )->get()->first();

                    if( ( $key == 'alternative_email' && $alternative_contact == null ) || ( $key == 'alternative_phone' && $alternative_contact == null ) )
                    {
                        $db_data = DB::table( 'user_contacts' )->insert( [
                            'contact' => $contact,
                            'contact_type' => $contact_type_arr[ $key ]->id,
                            'user_id' => $user[ 'updateId' ],
                        ] );
                    }
                    else $db_data = DB::table( $table_name_two )->where( $where )->update( [ 'contact' => $contact ] );
    
                    if( $db_data == 1 )
                        $updated[ $table_name_two ][ $key ] = true;    
                    else $updated[ $table_name_two ][ $key ] = false;
                }
            }

            if( ( in_array( true, $updated[ 'users'] ) ) || ( in_array( true, $updated[ 'user_contacts'] ) ) )
                return true;
        }

        return false;
    }
}
