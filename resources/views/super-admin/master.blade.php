<!--
         _____ _ _ __\/_____ __ _   ___ ___ ___ _ __\/___ _/___
        |_    | | |  ___/   |  \ | |   | __|   | |  ___/ |  __/
         _| | | | |___  | ^ | |  | | ^_| __| ^_| |___  | | |__
        |_____|_,_|_____|_|_|_|__| |_| |___|_|\ _|_____|_|____|

        Hvala što se interesujete za kod :)

        Kontakt za developere: kontakt@dusanperisic.com

-->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>Administracija</title>
        <!-- stilovi START::-->
        {!! HTML::style('css/templejtBackEnd.css') !!}
        {!! HTML::style('css/bootstrap.min.css') !!}
        {!! HTML::style('css/fontello.css') !!}
        {!! HTML::style('css/animation.css') !!}
        <!-- stilovi END::-->

        <!-- skripte START::-->
        {!! HTML::script('js/jquery-3.0.js') !!}
        {!! HTML::script('js/funkcije.js') !!}
        {!! HTML::script('tinymce/tinymce.min.js') !!}
        <style>h1,h2,p{text-align: center}</style>
        <!-- stilovi END::-->
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#dMenija">
                        <span class="sr-only">Prikaži menij</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{!! url('/') !!}"><span class="glyphicon glyphicon-home"></span> Magacin</a>
                </div>
                <div id="dMenija" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/administracija/korisnici"><span class="glyphicon glyphicon-user"></span> Korisnici</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-th-large"></span> Aplikacije</a></li>
                        <li><a href="/administracija/logout"><span class="glyphicon glyphicon-off"></span> Odjava</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container">
            @yield('content')
        </div>

        @yield('body')
        <script>$(function(){$('[data-toggle=tooltip]').tooltip()})</script>
        {!! HTML::script('js/bootstrap.min.js') !!}

    </body>
</html>
