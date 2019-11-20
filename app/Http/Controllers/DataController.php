<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DataController extends Controller
{
    protected $posts_per_page = 5;

    // Get User Contacts
    public function getUserContacts( $id )
    {
        $table_name = 'user_contacts';
        $linked_table_name = 'contact_type';

        $select_arr = [
            $table_name . '.id',
            $table_name . '.contact',
            $table_name . '.contact_type',
            $linked_table_name . '.contact as contact_type_name',
        ];

        return DB::table( $table_name )->leftJoin( $linked_table_name, $linked_table_name . '.id', $table_name . '.contact_type' )->select( $select_arr )->where( $table_name . '.user_id', $id )->get()->toArray();
    }

    // Fetch Users
    public function fetch_users( $search = '' )
    {
        $table_name = 'users';

        $select_arr = [ 'id', 'name', 'surname' ];

        if( $search != '' )
        {
            $where = [
                [ $table_name . '.name', 'like', '%' . $search . '%' ],
            ];

            $or = [
                [ $table_name . '.surname', 'like', '%' . $search . '%' ],
            ];

            $db_data = DB::table( $table_name )->select( $select_arr )->where( $where )->orWhere( $or )->orderBy( 'name', 'asc' )->distinct( 'id' )->paginate( $this->posts_per_page );
        }
        else
            $db_data = DB::table( $table_name )->select( $select_arr )->orderBy( 'name', 'asc' )->distinct( 'id' )->paginate( $this->posts_per_page );

        foreach( $db_data->items() as $key => $item )
            $db_data->items()[ $key ]->contacts = $this->getUserContacts( $item->id );
    
        return $db_data;
    } 

    // Delete User
    public function delete_user( $id )
    {    
        $table_name = 'users';
        $table_name_linked = 'user_contacts';

        if( \Schema::hasTable( $table_name ) )
        {
            $has_contacts = DB::table( $table_name_linked )->select( 'contact' )->where( 'user_id', $id )->get()->first();

            if( $has_contacts != null )
            {
                $db_data_linked = DB::table( $table_name_linked )->where( 'user_id', $id )->delete();
                $db_data = DB::table( $table_name )->where( 'id', $id )->delete();
                
                if( isset( $db_data ) && $db_data == 1 )
                    return true;    
                else return false;
            }
            else
            {
                $db_data = DB::table( $table_name )->where( 'id', $id )->delete();

                if( $db_data == 1 )
                    return true;    
                else return false;
            }
        }

        return false;
    }

    // Update User
    public function update_user( $user )
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
                        if( $contact == 'undefined' || $contact == '' )
                            continue;

                        $contacts = explode( ',', $contact );
                        
                        foreach( $contacts as $k => $c )
                        {
                            $cs = explode( 'new_', $c );

                            $db_data = DB::table( 'user_contacts' )->insert( [
                                'contact' => $cs[ 1 ],
                                'contact_type' => $contact_type_arr[ $key ]->id,
                                'user_id' => $user[ 'updateId' ],
                            ] );
                        }
                    }
                    else 
                    {
                        if( ( $key == 'alternative_email' && $alternative_contact != null ) || ( $key == 'alternative_phone' && $alternative_contact != null ) )
                        {
                            $contacts = explode( ',', $contact );
                           
                            foreach( $contacts as $k => $c )
                            {
                                $cs = explode( '_', $c );

                                if( $cs[ 1 ] == '' )
                                    $db_data = DB::table( $table_name_two )->where( 'id', $cs[ 0 ] )->delete();
                                else if( $cs[ 0 ] == 'new' )
                                {
                                    $db_data = DB::table( 'user_contacts' )->insert( [
                                        'contact' => $cs[ 1 ],
                                        'contact_type' => $contact_type_arr[ $key ]->id,
                                        'user_id' => $user[ 'updateId' ],
                                    ] );
                                }
                                else $db_data = DB::table( $table_name_two )->where( 'id', $cs[ 0 ] )->update( [ 'contact' => $cs[ 1 ] ] );
                            }
                        }
                        else $db_data = DB::table( $table_name_two )->where( $where )->update( [ 'contact' => $contact ] );
                    }
    
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
