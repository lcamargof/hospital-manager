<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta name="author" content="a2 Softway C.A.">
        <title>Hospital manager - @yield('title')</title>  {{--TITULO DE LA PAGINA--}}
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        @section('css') {{--GLOBAL CSS--}}
            <link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">
            <link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap-theme.min.css">
            <link media="all" type="text/css" rel="stylesheet" href="/css/font-awesome/css/font-awesome.min.css">
            <link media="all" type="text/css" rel="stylesheet" href="/css/style.css">
        @show
    </head>
    <body>
        {{-- NAVBAR --}}
        <div class="navbar navbar-inverse navbar-fixed-top" style="height: 56px;">
            <div class="navbar-header col-sm-4 col-xs-9">
                <a class="navbar-brand" href="{{ URL::to('/') }}"><i class="fa fa-heartbeat fa-lg"></i> Hospital Manager</a>
            </div>
            @if(Auth::check())
                <div class="navinfo col-sm-8 col-xs-3">            
                    <div class="navuserinfo hidden-xs navbar-right">

                        <h4 style="color:white;" >
                            @if(Auth::user()->role == 'doctor') 
                               Dr. {{ Auth::user()->doctor->name }}
                            @else
                                {{Auth::user()->user}}
                            @endif
                        </h4>

                        <div id="user_actions">
                            <a href="/logout">Log out</a>
                       </div>

                    </div>
                    <img src="/img/avatar.png" class="navbar-right" id="top-avatar" >
                </div>
            @endif
        </div>
        <div id="wrap">
            @yield('content') {{--CONTENIDO DE LA PAGINA--}}
        </div>
    </body>
</html>

@section('javascript')  {{--JAVASCRIPT BASICO--}}
    <script src="/js/base/jquery-1.11.3.min.js"></script>
    <script src="/js/base/bootstrap.min.js"></script>

    @section('Constructor')
        <script>
            window.php = window.php || {};
            php.role = "{{ (Auth::user()) ? Auth::user()->role : 'Guest' }}";
        </script>
    @show
@show

