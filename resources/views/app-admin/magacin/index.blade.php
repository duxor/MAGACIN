@extends('admin-master')
@section('content')
    <h2 style="text-align: left" id="proizvodi"><i class="glyphicon glyphicon-folder-open"></i> Magacini
        <button id="dugmeNovi" class="btn btn-primary" onClick="noviMagacin()" data-toggle="tooltip" title="Dodaj novi"><i class="glyphicon glyphicon-plus"></i></button>
        <button id="dugmeUcitaj" class="btn btn-primary" onClick="ucitajMagacine()" data-toggle="tooltip" title="Prikaži magacine" style="display:none"><i class="glyphicon glyphicon-th-large"></i></button>
    </h2>



    <script>
        $(function(){ucitajMagacine()})
        function ucitajMagacine(){
            $('#dugmeNovi').fadeIn();
            $('#dugmeUcitaj').hide();
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size:350%"></i></center>');
            $.post('/administracija/magacin/ucitaj',{
                _token:'{{csrf_token()}}'
            }, function(data){
                var magacini=JSON.parse(data);
                if(magacini.length<1){
                    $('#work-place').html('Ni jedan magacin se ne nalayi u evidenciji.');
                    return;
                }
                var ispis='<table class="table table-striped"><thead><tr><th></th><th>Naziv</th><th>Opis</th><th></th></tr></thead><tbody>';
                for(var i=0; i<magacini.length; i++)
                    ispis+='<tr>' +
                                '<td><a href="#" class="btn btn-lg btn-default" data-toggle="tooltip" title="Pregledaj proizvode u magacinu"><span class="glyphicon glyphicon-eye-open"></span></a></td>' +
                                '<td>'+magacini[i]['naziv']+'</td>' +
                                '<td>'+magacini[i]['opis']+'</td>' +
                                '<td>' +
                                    '<a href="#" class="btn btn-sm btn-info" data-toggle="tooltip" title="Ažuriraj podatke o magacinu" onclick="editMagacin('+magacini[i]['id']+')" style="margin-right: 5px"><span class="glyphicon glyphicon-pencil"></span></a>' +
                                    '<a href="#" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Ukloni magacin i sve proizvode iz njega" onclick="ukloniMagacin('+magacini[i]['id']+')"><span class="glyphicon glyphicon-trash"></span></a>' +
                                '</td>' +
                            '</tr>';
                ispis+='</tbody></table>';
                $('#work-place').hide();
                $('#work-place').html(ispis);
                $('#work-place').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            });
        }
        function noviMagacin(magacin){
            $('#dugmeNovi').hide();
            $('#dugmeUcitaj').fadeIn();

            $('#work-place').hide();
            $('#work-place').html('' +
            '<div id="forma" style="margin-top: 50px" class="form-horizontal">'+
                '{!!Form::hidden("_token",csrf_token())!!}'+
                (magacin?'<input name="id" value="'+magacin['id']+'" hidden="hidden">':'')+
                '<div id="dnaziv" class="form-group has-feedback">' +
                    '{!! Form::label("lnaziv","Naziv",["class"=>"control-label col-sm-2"]) !!}' +
                    '<div class="col-sm-10">' +
                        '<input name="naziv" value="'+(magacin?magacin['naziv']:'')+'" placeholder="Naziv" class="form-control" id="naziv">'+
                        '<span id="snaziv" class="glyphicon form-control-feedback"></span>' +
                    '</div>' +
                '</div>' +
                '<div id="dopis" class="form-group has-feedback">' +
                    '{!! Form::label("lopis","Opis",["class"=>"control-label col-sm-2"]) !!}' +
                    '<div class="col-sm-10">' +
                        '<textarea name="opis" placeholder="Opis" class="form-control" id="opis">'+(magacin?magacin['opis']:'')+'</textarea>'+
                        '<span id="sopis" class="glyphicon form-control-feedback"></span>' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-2"></div>' +
                    '<div class="col-sm-10">' +
                        '<button class="btn btn-lg btn-primary" onClick="sacuvajPodatke()" style="margin-right: 10px"><span class="glyphicon glyphicon-floppy-disk"></span> Sačuvaj</button>' +
                        '<button class="btn btn-lg btn-danger" onClick="ucitajMagacine()"><span class="glyphicon glyphicon-off"></span> Otkaži</button>' +
                    '</div>' +
                '</div>' +
            '</div>'+
            '<div id="wait" style="display:none"><center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center></div>' +
            '<div id="poruka" style="display: none"></div>');
            $('#work-place').fadeIn();
        }
        function sacuvajPodatke(){
            if(SubmitForm.check('forma'))
                Komunikacija.posalji("/administracija/magacin/azuriraj","forma","poruka","wait","forma");
        }
        function editMagacin(magacin){
            $('#dugmeNovi').hide();
            $('#dugmeUcitaj').fadeIn();
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%;margin-top:80px"></i></center>');
            $.post('/administracija/magacin/edit-ucitaj',
                    {
                        _token: '{{csrf_token()}}',
                        id: magacin
                    }, function (data) {
                        noviMagacin(JSON.parse(data))
                    });
        }
        function ukloniMagacin(id){
            $('#modalDialog').html('<h2>Da li ste sigurni da želite potpuno da uklonite magacin i sve proizvode iz njega?</h2>');
            $('#modalBody').html('<a href="#" class="btn btn-danger" style="margin-right: 10px" onclick="potpunoUkloniMagacin('+id+')"><i class="glyphicon glyphicon-trash"> Ukloni</i></a><a href="#" data-dismiss="modal" class="btn btn-primary"><i class="glyphicon glyphicon-off"></i> Odustani</a>');
            $('#modal').modal();
        }
        function potpunoUkloniMagacin(id){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size:350%"></i></center>');
            $('#modal').modal('hide');
            $.post('/administracija/magacin/ukloni',{
                _token:'{{csrf_token()}}',
                id:id
            },function(){
                ucitajMagacine();
            });
        }
    </script>
    <div id="work-place"></div>
    <i class="icon-spin6 animate-spin" style="font-size: 1px;color: rgba(0,0,0,0)"></i>
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" id="modalDialog"></div>
                <div class="modal-body" id="modalBody"></div>
            </div>
        </div>
    </div>
@endsection