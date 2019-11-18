<?php

namespace App;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CreateUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    public function create_user( $user_arr )
    {        
        $return = $exist = [];
        $return[ 'user' ] = false;

        if( isset( $user_arr['contacts'] ) && count( $user_arr['contacts'] ) > 0 )
        {
            $exist[ 'name' ] = DB::table( 'users' )->select( [ 'id', 'name'] )->where( 'name', '=', $user_arr[ 'name' ] )->get()->first();

            if( $exist[ 'name' ] != null )
                return $return;
 
            $exist[ 'contacts' ] = DB::table( 'user_contacts' )->select( 'contact' )->whereIn( 'contact', $user_arr[ 'contacts' ] )->get()->first();
             
            if( $exist[ 'contacts' ] != null )
                return $return;
                
            $contact_type_arr = DB::table( 'contact_type' )->select( [ 'id', 'contact' ] )->orderBy( 'id', 'asc' )->get()->keyBy( 'contact' )->toArray();

            $user = new User;    
            $user->name = $user_arr[ 'name' ];
            $user->surname = $user_arr[ 'surname' ];
            $user->created_at = NOW();        
            $user->save();

            if( isset( $user->id ) )
            {
                foreach( $user_arr[ 'contacts' ] as $key => $contact )
                {
                    if( ($key == 'alternative_email' && $contact == null) || ( $key == 'alternative_phone' && $contact == null ) )
                        continue;

                    DB::table( 'user_contacts' )->insert( [
                        'contact' => $contact,
                        'contact_type' => $contact_type_arr[ $key ]->id,
                        'user_id' => $user->id,
                    ] );
                }

                $return[ 'user' ] = true;
            }
        }

        return $return;
    }
}