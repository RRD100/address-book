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

                        if( $user_search != '' )
                        {                            
                            $user_search_r = '<span class="search-results">' . $user_search . '</span>';

                            $name_c = str_ireplace( $user_search, $user_search_r, $user->name );

                            $surname_c = str_ireplace( $user_search, $user_search_r, $user->surname );

                            $fullname_c = $name_c . ' ' . $surname_c;
                        }

                        $output .= '<tr>';

                        if( $is_admin )
                        {           
                            $output .= '<td>' . $count . '</td>';                        
                            $output .= '<td><input id="name' . $user->id . '" type="text" class="form-control" name="name" value="' . $name_c . '" required autofocus> <input id="surname' . $user->id . '" type="text" class="form-control" name="surname" value="' . $surname_c . '" required autofocus></td>';
                            $output .= '<td><input id="email' . $user->id . '" type="email" class="form-control" name="contacts[email]" value="' . $user->email . '" required></td>';
                            $output .= '<td><input id="alternative_email' . $user->id . '" type="email" class="form-control" name="contacts[alternative_email]" value="' . $user->alternative_email . '"></td>';
                            $output .= '<td><input id="phone' . $user->id . '" type="phone" class="form-control" name="contacts[phone]" value="' . $user->phone . '" required></td>';
                            $output .= '<td><input id="alternative_phone' . $user->id . '" type="phone" class="form-control" name="contacts[alternative_phone]" value="' . $user->alternative_phone . '"></td>';
                            $output .= '<td><a href="#" updateId="' . $user->id . '" class="btn btn-info btn-sm update-user">Update</a></td>';
                            $output .= '<td><a href="#" deleteId="' . $user->id . '" class="btn btn-info btn-sm delete-user">Delete</a></td>';
                        }
                        else 
                        {
                            $output .= '<td>' . $count . '</td>';                        
                            $output .= '<td>' . $fullname_c . '</td>';
                            $output .= '<td>' . $user->email . '</td>';
                            $output .= '<td>' . $user->alternative_email . '</td>';
                            $output .= '<td>' . $user->phone . '</td>';
                            $output .= '<td>' . $user->alternative_phone . '</td>';
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
    function fetch_data_users_reload()
    {
        $.ajax( {
            url: "/fetch_data_users?page=1",
            success: function( data )
            {
                alert( 'User Was Updated!' );
                $( '.results' ).html( data );
            }
        } );
    } 

    function update_user( updateId )
    {
        var updateId = updateId;
        var name = $( '#name' + updateId ).val();
        var surname = $( '#surname' + updateId ).val();
        var email = $( '#email' + updateId ).val();
        var alternative_email = $( '#alternative_email' + updateId ).val();
        var phone = $( '#phone' + updateId ).val();
        var alternative_phone = $( '#alternative_phone' + updateId ).val();

        $.ajax( {
            url: "/update_user?updateId=" + updateId + "&name=" + name + "&surname=" + surname + "&email=" + email + "&alternative_email=" + alternative_email + "&phone=" + phone + "&alternative_phone=" + alternative_phone,
            success: function( data )
            {
                if( data == 'true' )
                    fetch_data_users_reload();
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

    function delete_user(id) {
        $.ajax({
            url: "/delete_user?deleteId=" + id,
            success: function(data) {
                if (data == 'true')
                    fetch_data_users_reload();
                else alert('Operation Unsuccessful!');
            }
        });
    }

    $('.delete-user').click(function() {
        event.preventDefault();

        var id = $(this).attr('deleteId');

        var r = confirm('Are you sure you want to delete this user?');

        if (r == true)
            delete_user(id);
    });
});
</script>
<?php
}
?>