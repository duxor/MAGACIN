<!--
         _____ _ _ __\/_____ __ _   ___ ___ ___ _ __\/___ _/___
        |_    | | |  ___/   |  \ | |   | __|   | |  ___/ |  __/
         _| | | | |___  | ^ | |  | | ^_| __| ^_| |___  | | |__
        |_____|_,_|_____|_|_|_|__| |_| |___|_|\ _|_____|_|____|

        Hvala što se interesujete za kod :)

        Kontakt za developere: kontakt@dusanperisic.com

-->

<!DOCTYPE html>
<html lang="sr" class="no-skrollr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="google-translate-customization" content="63955273670ebbdc-a0af1b075be7ac02-g352fbbe38eded318-d">

    <title>@yield('title')</title>
    {!! HTML::style('css/templejt.css') !!}
    {!! HTML::style('css/bootstrap.min.css') !!}
    {!! HTML::style('css/parallax.css') !!}

    {!! HTML::script('js/jquery-3.0.js') !!}

    @if(isset($podaci['x']))
        <script>var mx="{!! $podaci['x'] !!}", my="{!! $podaci['y'] !!}";</script>
        {!! HTML::script('http://maps.googleapis.com/maps/api/js') !!}
        {!! HTML::script('js/gmap1.js') !!}
    @endif
    <style>@yield('style')</style>
</head>

<body>
    {{--navigacija START::--}}
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand scroll-link navbar-btn" data-id="">
                    <span class="glyphicon glyphicon-home"></span> Magacin
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">

                </ul>
                <ul class="nav navbar-nav navbar-right">


                </ul>
            </div>
        </div>
    </nav>
    {{--navigacija END::--}}
    <div class="container">
        @yield('content')
    </div>

    @yield('body')
    {!! HTML::script('js/skrollr.min.js') !!}
    <script type="text/javascript">
        skrollr.init({
            smoothScrolling: false,
            mobileDeceleration: 0.004
        });
    </script>

    {!! HTML::script('js/bootstrap.min.js') !!}
    {!! HTML::script('js/funkcije.js') !!}
</body>
</html>