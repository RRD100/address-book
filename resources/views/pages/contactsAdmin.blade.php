@extends( 'layouts.app' )

@section( 'title', 'Contacts Admin | Realm Digital' )

@section( 'content' )
<div class="container">
    <div class="row justify-content-center">
        <div class="heading"><h2>{{ $heading }}</h2></div><!-- heading -->

        <div class="col-md-12 box">    
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group input-user-search" id="input-user-search">
                                <input type="text" name="user_search" id="user_search" class="form-control" hasTabs="1" placeholder="Search by Name or Surname" />
                            </div><!-- input-user-search -->
                        </div><!-- col-md-5 -->

                        <div class="col-md-2">
                            <div class="filters-div">
                                <button type="button" name="search" id="search" class="btn btn-info btn-sm" hasTabs="1">Search</button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">Clear</button>
                            </div><!-- filters-div -->
                        </div><!-- col-md-2 -->

                        <div class="col-md-5"></div><!-- col-md-5 -->
                    </div><!-- row -->
                </div><!-- panel-heading -->
            </div><!-- panel panel-default -->
        </div><!-- col-md-12 box -->

        <div class="col-md-12 box">
            @if( $errors->any() )
                <div class="alert alert-danger alert-block">
                    <strong>{{ $errors->first() }}</strong>
                </div><!-- alert alert-danger alert-block -->
            @endif
            
            @if( session( 'message' ) )
                <div class="alert alert-success alert-block">
                    <strong>{{ session( 'message' ) }}</strong>
                </div><!-- alert alert-success alert-block -->
            @endif

            <ul class="nav nav-tabs">
                <li id="tab1" class="active"><a href="#tab-1">Edit Users</a></li>
                <li id="tab2"><a href="#tab-2">Create New User</a></li>
            </ul><!-- nav nav-tabs -->
            
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active results">
                    @include( 'pages.part.contactsResults' )
                </div><!-- tab-1 -->

                <div id="tab-2" class="tab-pane results-create-new-user">
                    @include( 'pages.part.createNewUserResults' )
                </div><!-- tab-2 -->
            </div><!-- tab-content -->
        </div><!-- col-md-12 box -->         
    </div><!-- row justify-content-center -->
</div><!-- container -->
@endsection