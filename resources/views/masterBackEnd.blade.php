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
                    <a class="navbar-brand" href="{!! url('/administracija') !!}"><span class="glyphicon glyphicon-home"></span> Magacin</a>
                </div>
                <div id="dMenija" class="collapse navbar-collapse">
                    @if(\App\Security::autentifikacijaTest())
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> Magacini <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/administracija/magacin"><span class="glyphicon glyphicon-eye-open"></span> Pregled</a></li>
                                <li><a href="/administracija/magacin/novi"><span class="glyphicon glyphicon-plus"></span> Dodaj novi</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-lamp"></span> Proizvodi <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/administracija/proizvod"><span class="glyphicon glyphicon-eye-open"></span> Pregled i ažuriranje</a></li>
                                <li><a href="/administracija/proizvod/novi"><span class="glyphicon glyphicon-plus"></span> Dodavanje novog</a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-search"></span> Pretraga</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-shopping-cart"></span>@if(\App\OsnovneMetode::nestanakProizvoda()>0)<span class="badge">{{\App\OsnovneMetode::nestanakProizvoda()}}</span>@endif Za narudžbu <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/administracija/proizvod/za-narudzbu"><span class="glyphicon glyphicon-eye-open"></span> Aktuelno</a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-briefcase"></span> Arhiva</a></li>
                            </ul>
                        </li>
                        <li><a href="/administracija/logout"><span class="glyphicon glyphicon-off"></span> Odjava</a></li>
                    </ul>
                    @endif
                </div>
            </div>
        </nav>
        
        <div class="container">
            @yield('content')
        </div>

        @yield('body')

        {!! HTML::script('js/bootstrap.min.js') !!}

    </body>
</html>
