@extends( 'layouts.app' )

@section( 'title', 'Contacts | Realm Digital' )

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
                                <input type="text" name="user_search" id="user_search" class="form-control" hasTabs="0" placeholder="Search by Name or Surname" />
                            </div><!-- input-user-search -->
                        </div><!-- col-md-5 -->

                        <div class="col-md-2">
                            <div class="filters-div">
                                <button type="button" name="search" id="search" class="btn btn-info btn-sm" hasTabs="0">Search</button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">Clear</button>
                            </div><!-- filters-div -->
                        </div><!-- col-md-2 -->

                        <div class="col-md-5"></div><!-- col-md-5 -->
                    </div><!-- row -->
                </div><!-- panel-heading -->
            </div><!-- panel panel-default -->
        </div><!-- col-md-12 box -->

        <div class="col-md-12 box results">
            @include( 'pages.part.contactsResults' )
        </div><!-- col-md-12 box -->         
    </div><!-- row justify-content-center -->
</div><!-- container -->
@endsection