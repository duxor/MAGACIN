@extends('admin-master')

@section('content')
    <p class="col-sm-12">Magacin v1.0</p>
    <br><hr>

    <div class="col-sm-4 form-inline">
        <button class="btn btn-sm btn-primary" style="margin-left: 3px;padding:3px 10px" onclick="pretraga('')" data-toggle="tooltip" title="Prikaži sve proizvode"><i class="glyphicon glyphicon-lamp"></i></button>
        <button class="btn btn-sm btn-danger" style="margin-left: 3px;padding:3px 10px" onclick="pretraga('',true)" data-toggle="tooltip" title="Prikaži proizode pri isteku zaliha"><i class="glyphicon glyphicon-alert"></i></button>
        <br clear="all"><hr class="col-sm-7"><br clear="all">
        <h3>Pretraga proizvoda</h3>
        <input class="form-control" name="pretraga" id="pretraga" style="width: 55%">
        <button class="btn btn-sm btn-default" style="margin-left: 3px;padding:3px 10px" onclick="pretraga()"><i class="glyphicon glyphicon-search"></i></button>
        <hr class="col-sm-7"><br clear="all">
        <div id="korpa">
            <h3>
                <i class="glyphicon glyphicon-shopping-cart"></i> Korpa
                <button class="btn btn-xs btn-primary" data-toggle="tooltip" title="Učitaj korpu" onclick="ucitajKorpu()"><i class="glyphicon glyphicon-refresh"></i></button>
                <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Isprazni korpu" onclick="ukloniIzKorpe('all')"><i class="glyphicon glyphicon-trash"></i></button>
            </h3>

            <div id="uKorpi"></div>
        </div>
    </div>
    <div class="col-sm-8" id="work-place"></div>
    <script>
        $(function(){ucitajKorpu(); pretraga('',true)})
        function pretraga(val,zalihe){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/proizvod/pretraga',{
                _token:'{{csrf_token()}}',
                pretraga:val===undefined?$('#pretraga').val():val,
                zalihe:zalihe
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
                                    '<th>Magacin</th>' +
                                    '<th>Šifra</th>' +
                                    '<th>Proizvod</th>' +
                                    '<th></th>'+
                                    '<th><i class="glyphicon glyphicon-stats" data-toggle="tooltip" title="Količina na stanju"></i></th>' +
                                    '<th><i class="glyphicon glyphicon-resize-horizontal" data-toggle="tooltip" title="Stolaža"></i></th>' +
                                    '<th><i class="glyphicon glyphicon-resize-vertical" data-toggle="tooltip" title="Polica"></i></th>' +
                                    '<th><i class="glyphicon glyphicon-indent-left" data-toggle="tooltip" title="Pozicija"></i></th>' +
                                '</tr>' +
                            '</thead>' +
                        '<tbody>';
                var warning=0;
                for(var i=0; i<rezultat.length; i++) {
                    warning=rezultat[i]['kolicina_stanje']<rezultat[i]['kolicina_min']?'danger':rezultat[i]['kolicina_stanje']==rezultat[i]['kolicina_min']?'warning':null;
                    ispis += '' +
                    '<tr class="'+warning+'">' +
                    '<td><a href="#">' + rezultat[i]['nazivmagacina'] + '</a></td>' +
                    '<td>' + rezultat[i]['sifra'] + '</td>' +
                    '<td>' + rezultat[i]['nazivproizvoda'] + '</td>' +
                    '<td><a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Dodaj u korpu" onclick="dodajUKorpu(this, ' + rezultat[i]['pid'] + ')"><i class="glyphicon glyphicon-check"></i></a>'+
                    '<td>' + rezultat[i]['kolicina_stanje'] + ( warning ? ( '<i class="badge">' + (rezultat[i]['kolicina_stanje']-rezultat[i]['kolicina_min']) + '</i>' ) : '' ) +'</td>' +
                    '<td>' + rezultat[i]['stolaza'] + '</td>' +
                    '<td>' + rezultat[i]['polica'] + '</td>' +
                    '<td>' + rezultat[i]['pozicija'] + '</td>' +
                    '</tr>';
                }
                ispis+='</tbody></table>';
                $('#work-place').hide();
                $('#work-place').html(ispis);
                $('#work-place').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            });
        }
        $(document).keypress(function(e){ if(e.which == 13) pretraga();})
        function dodajUKorpu(dugme,id){
            $(dugme).html('<i class="icon-spin6 animate-spin"></i>');
            $.post('/administracija/proizvod/dodaj-u-korpu',{
                _token:'{{csrf_token()}}',
                id:id
            },function(data){console.log(data);
                ucitajKorpu();
                $(dugme).html('<i class="glyphicon glyphicon-check"></i>');
            })
        }
        function ucitajKorpu(){
            $('#uKorpi').html('<center><i class="icon-spin6 animate-spin"></i></center>');
            $.post('/administracija/proizvod/ucitaj-korpu',{
                _token:'{{csrf_token()}}'
            },function(data){
                var rezultat=JSON.parse(data), i = 1, ispis='';
                if(rezultat){
                    ispis+='<table class="table table-condensed" style="font-size: 80%">';
                    $.each(rezultat, function(index, value) {
                        ispis+='<tr><td>'+i+'</td><td>'+value['sifra']+'</td><td>'+value['naziv']+'</td><td><button class="btn btn-xs btn-danger" onclick="ukloniIzKorpe('+index+')"><i class="glyphicon glyphicon-trash"></i></button></td></tr>';
                        i++;
                    });
                    ispis+='</table>';
                }
                else ispis='Nema proizvoda u korpi.';
                $('#uKorpi').hide();
                $('#uKorpi').html(ispis);
                $('#uKorpi').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            })
        }
        function ukloniIzKorpe(i){
            $('#uKorpi').html('<center><i class="icon-spin6 animate-spin"></i></center>');
            $.post('/administracija/proizvod/ukloni-iz-korpe',{
                _token:'{{csrf_token()}}',
                i:i
            },function(data){
                ucitajKorpu();
            })
        }
    </script>
    <i class="icon-spin6 animate-spin" style="font-size: 1px;color:rgba(0,0,0,0)"></i>
@endsection