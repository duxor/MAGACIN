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
        {!! HTML::style('css/datepicker.css') !!}
        <!-- stilovi END::-->

        <!-- skripte START::-->
        {!! HTML::script('js/jquery-3.0.js') !!}
        {!! HTML::script('js/funkcije.js') !!}
        {!! HTML::script('tinymce/tinymce.min.js') !!}
        {!! HTML::script('js/datepicker.js') !!}
        <!-- stilovi END::-->
        <style>h1,h2,p{text-align: center}</style>
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
                    <a class="navbar-brand" href="/administracija"><span class="glyphicon glyphicon-home"></span> Magacin</a>
                </div>
                <div id="dMenija" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/administracija/proizvod" data-toggle="tooltip" title="Proizvodi" data-placement="bottom"><i class="glyphicon glyphicon-lamp"></i></a></li>
                        <li><a href="/administracija/korisnici" data-toggle="tooltip" title="Korisnici" data-placement="bottom"><i class="glyphicon glyphicon-user"></i></a></li>
                        <li><a href="/administracija/fakture" data-toggle="tooltip" title="Fakture" data-placement="bottom"><i class="glyphicon glyphicon-tags"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="Tehnička podrška" data-placement="bottom" onclick="tp.modal()"><i class="glyphicon glyphicon-wrench"></i></a></li>
                        <li><a href="/administracija/logout" data-toggle="tooltip" title="Odjava" data-placement="bottom"><i class="glyphicon glyphicon-off"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="tehnickaPodrskaModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h2>Tehnička podrška</h2>
                    </div>
                    <div class="modal-body form-horizontal">
                        <p style="text-align: justify">Kontaktirajte Vašu tehničku podršku i direktno utičite na razvoj platforme. Šaljite nam Vaša zapažalja, utiske i unapređenja koja želite da vidite u sastavu platforme. Naš tim će pažljivo razmotriti svaku Vašu poruku i shodno tome, planirati buduće verzije poslovne aplikacije.</p>
                        <div class="form-group">
                            <label class="col-sm-4">Naslov</label>
                            <div class="col-sm-8"><input class="form-control" name="naslov"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4">Poruka</label>
                            <div class="col-sm-8"><textarea class="form-control" name="poruka"></textarea></div>
                        </div>
                        <div class="form-group"><label class="col-sm-4"></label><div class="col-sm-8">
                            <button class="btn btn-primary" onclick="tp.posalji()"><i class="glyphicon glyphicon-envelope"></i> Pošalji</button>
                        </div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" style="width: 98%">
            @yield('content')
        </div>
        @yield('body')
        <script>
        $(function(){$('[data-toggle=tooltip]').tooltip()})
        var tp={
            modal:function(){
                $('#tehnickaPodrskaModal').modal()
            },
            posalji:function(){
                $.post('/posalji',{_token:'{{csrf_token()}}',naslov:$('[name=naslov]').val(),poruka:$('[name=poruka]').val()},function(data){
                    $('.modal-body').html(data);
                })
            }
        }
        </script>
        {!! HTML::script('js/bootstrap.min.js') !!}
    </body>
</html>
