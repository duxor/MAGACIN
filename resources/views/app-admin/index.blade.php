@extends('admin-master')

@section('content')
    <p class="col-sm-12">Magacin v1.0</p>
    <br><hr>

    <div class="col-sm-3 form-inline">
        <h3>Pretraga proizvoda</h3>
        <input class="form-control" name="pretraga" id="pretraga">
        <button class="btn btn-sm btn-default" style="margin-left: 3px;padding:3px 10px" onclick="pretraga()"><i class="glyphicon glyphicon-search"></i></button>
    </div>
    <div class="col-sm-9" id="work-place"></div>
    <script>
        function pretraga(){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/proizvod/pretraga',{
                _token:'{{csrf_token()}}',
                pretraga:$('#pretraga').val()
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
                                    '<th>Naziv magacina</th>' +
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
                    '<td><a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Dodaj u fakturu"><i class="glyphicon glyphicon-check"></i></a>'+
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
    </script>
    <i class="icon-spin6 animate-spin" style="font-size: 1px;color:rgba(0,0,0,0)"></i>
@endsection