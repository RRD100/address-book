<div class="center">{!! $users->links() !!}</div><!-- center -->

<div class="panel panel-default">    
    <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    Total Records (<b><span id="total_records">{{ $users->total() }}</span></b>)
                    <br>
                    Displayed Records (<b><span id="displayed_records">{{ count( $users ) }}</span></b>)
                </div><!-- col-md-3 -->
            </div><!-- row -->
    </div><!-- panel-heading -->
   
    <div class="panel-body nopadding">
        <div class="table-responsive">
            <table id="all-users-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <?php
                            if( $is_admin )
                            {
                        ?>
                            <th width="2%">#</th>
                            <th width="18%">Full Name</th>
                            <th width="20%">Email</th>
                            <th width="20%">Alternative Email</th>
                            <th width="15%">Phone</th> 
                            <th width="15%">Alternative Phone</th> 
                            <th width="5%">Update</th> 
                            <th width="5%">Delete</th>
                        <?php
                            } else {
                        ?>
                            <th width="2%">#</th>
                            <th width="18%">Full Name</th>
                            <th width="20%">Email</th>
                            <th width="20%">Alternative Email</th>
                            <th width="20%">Phone</th> 
                            <th width="20%">Alternative Phone</th>                              
                        <?php
                            }
                        ?>
                    </tr>
                </thead>
                <tbody class="tbody">
                
                <?php
                    $output = '';

                    if( $users->currentPage() != 1 )
                        $count = $users->perPage() * ( $users->currentPage() - 1 ) + 1;
                    else $count = 1;

                    foreach( $users as $user )
                    {
                        $name_c = $user->name;
                        $surname_c = $user->surname;
                        $fullname_c = $name_c . ' ' . $surname_c;
                        $email = $alternative_email = $phone = $alternative_phone = '';

                        if( $user_search != '' )
                        {            
                            if( $is_admin )                
                                $user_search_r = $user_search;
                            else $user_search_r = '<span class="search-results">' . $user_search . '</span>';

                            $name_c = str_ireplace( $user_search, $user_search_r, $user->name );

                            $surname_c = str_ireplace( $user_search, $user_search_r, $user->surname );

                            $fullname_c = $name_c . ' ' . $surname_c;
                        }

                        $output .= '<tr>';

                        foreach( $user->contacts as $contact )
                        {
                            if( $is_admin )
                            {
                                $type = str_replace( 'alternative_', '', $contact->contact_type_name );

                                ${$contact->contact_type_name} .= '<input id="' . $contact->id . '" type="' . $type . '" class="form-control ' . $contact->contact_type_name . $user->id . '" name="contacts[' . $contact->contact_type_name . '][]" value="' . $contact->contact . '"><br>';
                            }
                            else ${$contact->contact_type_name} .= $contact->contact . '<br>';
                        }

                        if( $is_admin )
                        {  
                            $add_emails = '<div class="add_fields_emails_edit btn btn-primary" parentID="alternative_emails_edit' . $count . '" classID="alternative_email' . $count . '">Add More Fields</div>';

                            $add_phones = '<div class="add_fields_phones_edit btn btn-primary" parentID="alternative_phones_edit' . $count . '" classID="alternative_phone' . $count . '">Add More Fields</div>';

                            $output .= '<td>' . $count . '</td>';                        
                            $output .= '<td><input id="name' . $user->id . '" type="text" class="form-control" name="name" value="' . $name_c . '" required autofocus> <input id="surname' . $user->id . '" type="text" class="form-control" name="surname" value="' . $surname_c . '" required autofocus></td>';
                            $output .= '<td>'. $email .'</td>';
                            $output .= '<td>'. $alternative_email . '<div class="alternative_emails_edit' . $count . '"></div>'. $add_emails . '</td>';
                            $output .= '<td>'. $phone .'</td>';
                            $output .= '<td>'. $alternative_phone . '<div class="alternative_phones_edit' . $count . '"></div>'. $add_phones . '</td>';
                            $output .= '<td><a href="#" updateId="' . $user->id . '" class="btn btn-info btn-sm update-user">Update</a></td>';
                            $output .= '<td><a href="#" deleteId="' . $user->id . '" class="btn btn-info btn-sm delete-user">Delete</a></td>';
                        }
                        else 
                        {
                            $output .= '<td>' . $count . '</td>';                        
                            $output .= '<td>' . $fullname_c . '</td>';
                            $output .= '<td>'. $email .'</td>';
                            $output .= '<td>' . $alternative_email . '</td>';
                            $output .= '<td>'. $phone .'</td>';
                            $output .= '<td>' . $alternative_phone . '</td>';
                        }

                        $output .= '</tr>';
                        
                        $count++;
                    }
                    
                    echo $output;
                ?>
                </tbody>                            
            </table>
        </div><!-- table-responsive -->        
    </div><!-- panel-body nopadding -->
</div><!-- panel panel-default -->

<div class="center">{!! $users->links() !!}</div><!-- center -->

<?php
if( $is_admin )
{
?>
<script>
$(document).ready(function() {
    function fetch_data_users_reload( a )
    {
        $.ajax( {
            url: "/fetch_data_users?page=1",
            success: function( data )
            {
                $( '.results' ).html( data );

                alert( a );

                location.reload();
            }
        } );
    } 

    function update_user( updateId )
    {
        var updateId = updateId;
        var name = $( '#name' + updateId ).val();
        var surname = $( '#surname' + updateId ).val(); 
        var email = $( '.email' + updateId ).val();
        var phone = $( '.phone' + updateId ).val();

        var alternative_email = [];

        $( '.alternative_email' + updateId ).each( function( index, elem )
        {
            alternative_email[ index ] = [ $( elem ).attr( 'id' ) + '_' + $( elem ).val() ];
        } );  

        var alternative_phone = [];

        $( '.alternative_phone' + updateId ).each( function( index, elem )
        {
            alternative_phone[ index ] = [ $( elem ).attr( 'id' ) + '_' + $( elem ).val() ];
        } );  

        $.ajax( {
            url: "/update_user?updateId=" + updateId + "&name=" + name + "&surname=" + surname + "&email=" + email + "&alternative_email=" + alternative_email + "&phone=" + phone + "&alternative_phone=" + alternative_phone,
            success: function( data )
            {
                if( data == 'true' )
                    fetch_data_users_reload( 'User Was Updated!' );
                else alert( 'User Was Not Updated!' );
            }
        } );
    }

    $( '.update-user' ).click( function()
    {
        event.preventDefault();        

        var updateId = $( this ).attr( 'updateId' );

        update_user( updateId );
    } );

    function delete_user( id ) {
        $.ajax({
            url: "/delete_user?deleteId=" + id,
            success: function( data ) 
            {
                if( data == 'true' )
                    fetch_data_users_reload( 'User Was Deleted!' );
                else alert( 'Operation Unsuccessful!');
            }
        });
    }

    $( '.delete-user' ).click( function() 
    {
        event.preventDefault();

        var id = $( this ).attr( 'deleteId' );

        var r = confirm('Are you sure you want to delete this user?' );

        if( r == true )
            delete_user( id );
    }) ;
} );
</script>
<?php
}
?>