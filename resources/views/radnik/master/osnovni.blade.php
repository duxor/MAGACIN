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
        {!! HTML::script('js/fakturisanje.js') !!}
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

        <div class="container" style="width: 98%">
            <div class="col-sm-4 form-inline">
                    <button class="btn btn-sm btn-primary" style="margin-left: 3px;padding:3px 10px" onclick="pretraga('')" data-toggle="tooltip" title="Prikaži sve proizvode"><i class="glyphicon glyphicon-lamp"></i></button>
                    <button class="btn btn-sm btn-danger" style="margin-left: 3px;padding:3px 10px" onclick="pretraga('',true)" data-toggle="tooltip" title="Prikaži proizode pri isteku zaliha"><i class="glyphicon glyphicon-alert"></i></button>
                    <br clear="all"><hr class="col-sm-7"><br clear="all">
                    <h3>Pretraga proizvoda <input id="samoMagacin" type="checkbox" data-toggle="tooltip" title="Pretraži samo proizvode iz magacina." checked></h3>
                    <input class="form-control" name="pretraga" id="pretraga" style="width: 55%">
                    <button class="btn btn-sm btn-default" style="margin-left: 3px;padding:3px 10px" onclick="pretraga()"><i class="glyphicon glyphicon-search"></i></button>
                    <hr class="col-sm-7"><br clear="all">
                    <div id="korpa">
                        <h3>
                            <i class="glyphicon glyphicon-shopping-cart"></i> Korpa
                            <button class="btn btn-xs btn-primary" data-toggle="tooltip" title="Učitaj korpu" onclick="korpa.ucitaj()"><i class="glyphicon glyphicon-refresh"></i></button>
                            <button id="korpaPRED" style="display: none" class="btn btn-xs btn-success" data-toggle="tooltip" title="Štampaj predračun" onclick="fakturisanje.kreiraj(3)"><i class="glyphicon glyphicon-barcode"></i></button>
                            <button id="korpaNAR" style="display: none" class="btn btn-xs btn-success" data-toggle="tooltip" title="Izvrši narudžbu (kupovinu)" onclick="fakturisanje.kreiraj(1)"><i class="glyphicon glyphicon-file"></i></button>
                            <button id="korpaULAZ" style="display: none" class="btn btn-xs btn-info" data-toggle="tooltip" title="Evidentiraj pristiglu robu" onclick="fakturisanje.kreiraj(4)"><i class="glyphicon glyphicon-log-in"></i></button>
                            <button id="korpaDEL" style="display: none" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Isprazni korpu" onclick="korpa.ukloni('all')"><i class="glyphicon glyphicon-trash"></i></button>
                        </h3>
                        <div id="uKorpi"></div>
                    </div>
            </div>
            <div class="col-sm-8">
                @yield('content')
            </div>
        </div>
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
        <script>
        $(function(){korpa.ucitaj()});
        var korpa={
            token:'{{csrf_token()}}',
            dodaj:function(dugme,id){
                $(dugme).html('<i class="icon-spin6 animate-spin"></i>');
                $.post('/administracija/proizvod/dodaj-u-korpu',{
                    _token:korpa.token,
                    id:id
                },function(data){
                    korpa.ucitaj();
                    $(dugme).html('<i class="glyphicon glyphicon-check"></i>');
                })
            },
            ucitaj:function(){
                $('#uKorpi').html('<center><i class="icon-spin6 animate-spin"></i></center>');
                $.post('/administracija/proizvod/ucitaj-korpu',{
                     _token:korpa.token
                },function(data){
                    var rezultat=JSON.parse(data), i = 1, ispis='';
                    if(!jQuery.isEmptyObject(rezultat)){
                        $('#korpaNAR').show();
                        $('#korpaDEL').show();
                        $('#korpaPRED').show();
                        $('#korpaULAZ').show();
                        ispis+='<table class="table table-condensed" style="font-size: 80%">';
                        $.each(rezultat, function(index, value) {
                            ispis+='<tr><td>'+i+'</td><td>'+value['sifra']+'</td><td>'+value['naziv']+'</td><td><button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Ukloni iz korpe" onclick="korpa.ukloni('+index+')"><i class="glyphicon glyphicon-trash"></i></button></td></tr>';
                            i++;
                        });
                        ispis+='</table>';
                    }else{
                        $('#korpaNAR').hide();
                        $('#korpaDEL').hide();
                        $('#korpaPRED').hide();
                        $('#korpaULAZ').hide();
                        ispis='Nema proizvoda u korpi.';
                    }
                    $('#uKorpi').hide();
                    $('#uKorpi').html(ispis);
                    $('#uKorpi').fadeIn();
                    $('[data-toggle=tooltip]').tooltip();
                })
            },
            ukloni:function(i){
                $('#uKorpi').html('<center><i class="icon-spin6 animate-spin"></i></center>');
                $.post('/administracija/proizvod/ukloni-iz-korpe',{
                    _token:korpa.token,
                    i:i
                },function(data){
                    korpa.ucitaj();
                })
            }
        }
        var tp={
            modal:function(){
                $('#tehnickaPodrskaModal').modal()
            },
            posalji:function(){
                $.post('/posalji',{_token:korpa.token,naslov:$('[name=naslov]').val(),poruka:$('[name=poruka]').val()},function(data){
                    $('.modal-body').html(data);
                })
            }
        }
        function pretraga(val,istekZaliha){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/proizvod/pretraga',{
                _token:'{{csrf_token()}}',
                pretraga:val===undefined?$('#pretraga').val():val,
                istekZaliha:istekZaliha,
                samoMagacin:$('#samoMagacin').is(':checked')
            },function(data){
                var rezultat=JSON.parse(data);
                if(rezultat.length<1){
                    $('#work-place').hide();
                    $('#work-place').html('Nema podataka za navedenu pretragu. Pokušajte sa drugim unosom.');
                    $('#work-place').fadeIn();
                    return;
                }
                var ispis='<style>#tab *{text-align: center}</style>' +
                        '<table id="tab" class="table table-striped table-hover table-condensed">' +
                            '<thead>' +
                                '<tr>' +
                                    '<th></th>'+
                                    ($('#samoMagacin').is(':checked')?'<th>Magacin</th>':'') +
                                    '<th>Šifra</th>' +
                                    '<th>Proizvod</th>' +
                                    '<th></th>'+
                                    ($('#samoMagacin').is(':checked')?
                                        '<th>&sum;</th>'+
                                        '<th><i class="glyphicon glyphicon-stats" data-toggle="tooltip" title="Količina na stanju"></i></th>' +
                                        '<th><i class="glyphicon glyphicon-resize-horizontal" data-toggle="tooltip" title="Stolaža"></i></th>' +
                                        '<th><i class="glyphicon glyphicon-resize-vertical" data-toggle="tooltip" title="Polica"></i></th>' +
                                        '<th><i class="glyphicon glyphicon-indent-left" data-toggle="tooltip" title="Pozicija"></i></th>'
                                    :'') +
                                '</tr>' +
                            '</thead>' +
                        '<tbody>';
                var warning=0;
                for(var i=0; i<rezultat.length; i++) {
                    warning=$('#samoMagacin').is(':checked')?(rezultat[i]['ukupno_na_stanju']<rezultat[i]['kolicina_min']?'danger':rezultat[i]['ukupno_na_stanju']==rezultat[i]['kolicina_min']?'warning':null):null;
                    ispis += '' +
                    '<tr class="'+warning+'">' +
                    '<td><button class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="bottom" data-template=\'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:rgba(0,0,0,0)"></div></div>\' data-html="true" title="<img src=\''+rezultat[i]['foto']+'\' style=\'width:100%\'>"><i class="glyphicon glyphicon-picture"></i></button></td>'+
                    ($('#samoMagacin').is(':checked')?'<td><a href="#">' + rezultat[i]['nazivmagacina'] + '</a></td>':'') +
                    '<td>' + rezultat[i]['sifra'] + '</td>' +
                    '<td>' + rezultat[i]['nazivproizvoda'] + '</td>' +
                    '<td><a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Dodaj u korpu" onclick="korpa.dodaj(this, ' + rezultat[i]['pid'] + ')"><i class="glyphicon glyphicon-check"></i></a>';

                    if($('#samoMagacin').is(':checked')){
                        ispis+='<th><i data-toggle="tooltip" title="Minimalna kolicina: ' + rezultat[i]['kolicina_min'] + '">' + rezultat[i]['ukupno_na_stanju'] +'</i></td>';
                        for(var j=0; j<rezultat[i]['pozicije'].length; j++)
                        ispis+=(j>0?'<td></td><td></td><td></td><td></td><td></td><td></td>':'')+
                            '<td>' + rezultat[i]['pozicije'][j]['kolicina_stanje'] +'</td>' +
                            '<td>' + rezultat[i]['pozicije'][j]['stolaza'] + '</td>' +
                            '<td>' + rezultat[i]['pozicije'][j]['polica'] + '</td>' +
                            '<td>' + rezultat[i]['pozicije'][j]['pozicija'] + '</td>' +
                        '</tr>';
                    }
                }
                ispis+='</tbody></table>';
                $('#work-place').hide();
                $('#work-place').html(ispis);
                $('#work-place').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            });
        }
        </script>
        @yield('body')
        <script>$(function(){$('[data-toggle=tooltip]').tooltip()})</script>
        {!! HTML::script('js/bootstrap.min.js') !!}
    </body>
</html>
