@extends('app-admin.master.prazan')
@section('content')
    <h2 style="text-align: left" id="proizvodi"><i class="glyphicon glyphicon-tags"></i> Fakture
        <div class="form-inline" style="float: right">
            <div class="form-group">{!!Form::select('pretraga_godine',$godine,0,['class'=>'form-control','onchange'=>'fakture.ucitaj()'])!!}</div>
            <div class="form-group">{!!Form::select('pretraga_vrsta_fakture',$vrsta_fakture,0,['class'=>'form-control','onchange'=>'fakture.ucitaj()'])!!}</div>
        </div>
    </h2>
    <hr>

    <script>
        $(document).ready(function(){fakture.ucitaj()})
        var fakture={
            token:'{{csrf_token()}}',
            ucitaj:function(){
                $.post('/administracija/fakture/ucitaj',{_token:fakture.token,pretraga_vrsta_fakture:$('[name=pretraga_vrsta_fakture]').val(),pretraga_godine:$('[name=pretraga_godine]').val()},function(data){
                    data=JSON.parse(data);
                    if(data.length<1){
                        $('#work-place').html('<h2>Ne postoji ni jedna faktura u evidenciji.</h2>');
                        return;
                    }
                    var ispis='<table class="table table-striped table-hover"><tr><th>Broj fakture</th><th>Datum</th><th>Korisnik</th><th></th></tr>';
                    for(var i=0; i<data.length; i++){
                        ispis+='<tr><td>'+data[i]['broj_fakture']+'</td><td>'+data[i]['datum_narudzbe']+'</td><td>'+data[i]['korisnik']+'</td><td><a target="_blank" href="'+data[i]['pdf_link']+'" class="btn btn-xs btn-info" style="margin-right:5px" data-toggle="tooltip" title="Prikaži fakturu"><i class="glyphicon glyphicon-picture"></i></a><button class="btn btn-xs btn-danger" onclick="fakture.pripremiZaBrisanje('+data[i]['id']+')" data-toggle="tooltip" title="Ukloni fakturu"><i class="glyphicon glyphicon-trash"></i></button></tr>';
                    }
                    $('#work-place').html(ispis+'</table>');
                    $('[data-toggle=tooltip]').tooltip();
                })
            },
            pretrazi:function(){

            },
            pripremiZaBrisanje:function(id,kid){
                if(!$('#ukloni-modal').length)
                    $('body').append('<div id="ukloni-modal" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">&times;</button><h2></h2></div><div class="modal-body"><div class="alert alert-warning">Ukoliko uklonite fakturu biće uklonjeni i svi podaci vezani za nju. Da li ste sigurni da želite da uklonite navedenu?</div><button id="ukloniDugme" class="btn btn-danger" onclick="fakture.ukloni('+id+')"><i class="glyphicon glyphicon-trash"></i> Ukloni</button><button class="btn btn-primary" data-dismiss="modal"><i class="glyphicon glyphicon-off"></i> Otkaži</button></div></div></div></div>');
                else $('#ukloniDugme').attr('onclick','fakture.ukloni('+id+')');
                $('#ukloni-modal').modal('show');
            },
            ukloni:function(id,kid){
                $.post('/administracija/fakture/ukloni',{_token:fakture.token,id:id},function(){
                    $('#ukloni-modal').modal('hide');
                    fakture.ucitaj();
                })
            }
        }
    </script>
    <div id="work-place">Pregled i pretraga faktura je u pripremi.</div>
    <i class='icon-spin6 animate-spin' style="font-size: 1px;color:rgba(0,0,0,0)"></i>

@endsection