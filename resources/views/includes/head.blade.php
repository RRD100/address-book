<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield( 'title', 'Realm Digital' )</title>

<!-- Scripts -->
<script src="{{ asset( 'js/script.js' ) }}" defer></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">

<!-- Styles -->
<link href="{{ asset( 'css/app.css' ) }}" rel="stylesheet">
<link href="{{ asset( 'css/style.css' ) }}" rel="stylesheet">

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">