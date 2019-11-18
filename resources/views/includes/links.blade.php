
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="links">
                        @if( Request::is( 'contacts' ) ) <span class="active">Contacts</span> @else <a href="/contacts">Contacts</a> @endif
                        @if( Request::is( 'admin' ) ) <span class="active">Contacts Admin</span> @else <a href="/admin">Contacts Admin</a> @endif
                    </div><!-- links -->
                </div><!-- card-header -->
            </div><!-- card -->
        </div><!-- col-md-12 -->
    </div><!-- row justify-content-center -->
</div><!-- container -->