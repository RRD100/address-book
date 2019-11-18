<!DOCTYPE html>
<html lang="{{ str_replace( '_', '-', app()->getLocale() ) }}" class="no-js">
<head>
    @include( 'includes.head' )
    @include( 'includes.header-scripts' )
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light header-bg-color shadow-sm">
            @include( 'includes.header' )
        </nav>

        <main class="py-4">
            @include( 'includes.links' )

            @yield( 'content' )
        </main>

        <footer>
            @include( 'includes.footer' )
        </footer>
    </div>

    @include( 'includes.footer-scripts' )
</body>
</html>
