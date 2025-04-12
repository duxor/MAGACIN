@extends('super-admin.master.osnovni')
@section('content')
    <h2 style="text-align: left" id="aplikacije"><i class="glyphicon glyphicon-th-large"></i> Aplikacije
        <button id="dugmeNovi" class="btn btn-primary" onClick="aplikacije.nova()" data-toggle="tooltip" title="Dodaj novu aplikaciju"><i class="glyphicon glyphicon-plus"></i></button>
        <button id="dugmeUcitaj" class="btn btn-primary" onClick="aplikacije.ucitaj()" data-toggle="tooltip" title="Prikaži aplikacije" style="display:none"><i class="glyphicon glyphicon-user"></i></button>
        <div class="form-inline" style="float: right">
            <button class="btn btn-sm btn-default" style="padding: 1.5px 10px" data-toggle="tooltip" title="Prikaži sve aplikacije" onclick="aplikacije.ucitaj()"><i class="glyphicon glyphicon-th"></i></button>
            <div class="form-group">{!!Form::text('pretraga_aplikacije',null,['class'=>'form-control','id'=>'pretraga_aplikacije'])!!}</div>
            <button class="btn btn-sm btn-default" style="padding: 3px 10px" data-toggle="tooltip" title="Pronađi aplikaciju" onclick="aplikacije.pretrazi()"><i class="glyphicon glyphicon-search"></i></button>
        </div>
    </h2>
    <hr>
    <div id="work-place">Aplikativna administracija.</div>
    <div id="modalUniverzalni" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modUnBo" class="modal-body"></div>
                <div class="modal-footer">
                    <button id="modUnFoBtn" class="btn btn-danger"></button>
                    <button class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-off"></i> Otkaži</button>
                </div>
            </div>
        </div>
    </div>
    <i class='icon-spin6 animate-spin' style="font-size: 1px;color:rgba(0,0,0,0)"></i>

    {!! HTML::style('/dragdrop/css/fileinput.css') !!}
    {!! HTML::script('/dragdrop/js/fileinput.min.js') !!}
    <script>
    $(document).ready(function(){aplikacije.ucitaj()})
    var aplikacije={
        token:'{{csrf_token()}}',
        korisnici:'',
        pretrazi:function(){
            aplikacije.ucitaj($('#pretraga_aplikacije').val());
        },
        ucitajKorisnike:function(){
            $.post('/administracija/aplikacije-ucitaj-korisnike',{_token:aplikacije.token},function(data){
                data=JSON.parse(data);
                for(var i=0; i<data.length; i++)
                    aplikacije.korisnici+='<option value="'+data[i]['id']+'">'+data[i]['prezime']+' '+data[i]['ime']+'</option>';
            })
        },
        ucitaj:function(pretraga){
        if(aplikacije.korisnici.length<1) aplikacije.ucitajKorisnike();
        var ispis="";
            $.post('/administracija/ucitaj-aplikacije',{_token:aplikacije.token,pretraga:pretraga},function(data){
                data=JSON.parse(data);
                if(data.length<1){
                    $('#work-place').html('<h3>Ne postoji ni jedna aplikacija u evidenciji.');
                    return;
                }
                ispis+='<table class="table table-striped"><thead>' +
                 '<tr><th></th><th>Naziv</th><th>Slug</th><th>Vlasnik</th><th>Email</th><th>Napomena</th><th></th><th></th></tr></thead><tbody>';
                 for(var i=0;i<data.length;i++){
                    ispis+=
                    '<tr><td><button id="logo-'+data[i]['id']+'" data-src="'+(data[i]['logo']?data[i]['logo']:'/img/aplikacije/'+data[i]['slug']+'/logo.jpg')+'" class="btn btn-xs btn-info" data-toggle="tooltip" data-template=\'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:rgba(0,0,0,0)"></div></div>\' data-placement="bottom" data-html="true" title="<img src=\''+(data[i]['logo']?data[i]['logo']:'/img/aplikacije/'+data[i]['slug']+'/logo.jpg')+'\'>"><i class="glyphicon glyphicon-picture"></i></button></td>' +
                    '<td id="naziv-'+data[i]['id']+'">'+data[i]['naziv']+'</td>' +
                    '<td id="slug-'+data[i]['id']+'">'+data[i]['slug']+'</td>' +
                    '<td><select class="form-control" id="vlasnik-'+data[i]['id']+'" onchange="aplikacije.promijeniVlasnistvo(this,'+data[i]['id']+')">'+aplikacije.korisnici+'</select></td>'+
                    '<td id="email-'+data[i]['id']+'">'+(data[i]['email']?data[i]['email']:'')+'</td>'+
                    '<td id="napomena-'+data[i]['id']+'">'+(data[i]['napomena']?data[i]['napomena']:'')+'</td>'+
                    '<td class="aktiv-'+data[i]['id']+'" data-aktivan="'+data[i]['aktivan']+'">'+(data[i]['aktivan']?'<i class="glyphicon glyphicon-ok"></i>':'<i class="glyphicon glyphicon-remove"></i>')+'</td>'+
                    '<td>' +
                        '<button class="btn btn-xs btn-info" data-toggle="tooltip" title="Ažuriraj" onclick="aplikacije.edit('+data[i]['id']+')" style="margin-right:3px"><i class="glyphicon glyphicon-pencil"></i></button>' +
                        '<button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Promjena statusa (ne)aktivan" onclick="aplikacije.status('+data[i]['id']+')" style="margin-right:3px"><i class="glyphicon glyphicon-lock"></i></button>' +
                        '<button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Ukloni aplikaciju" onclick="aplikacije.ukloni('+data[i]['id']+')"><i class="glyphicon glyphicon-trash"></i></button>' +
                    '</td></tr>';
                 }
                 $('#work-place').html(ispis+'</tbody></table>');
                 for(var i=0; i<data.length; i++)
                    $('select#vlasnik-'+data[i]['id']+'>option[value='+data[i]['korisnici_id']+']').prop('selected',true);
                 $('[data-toggle=tooltip]').tooltip();
            })
        },
        promijeniVlasnistvo:function(el,id){
            $(el).hide();
            $.post('/administracija/aplikacija-promijeni-vlasnistvo',{_token:aplikacije.token,id:id,vlasnik:parseInt($(el).val())},function(){
                $(el).fadeIn();
            })
        },
        nova:function(app){
            if(aplikacije.korisnici.length<1) aplikacije.ucitajKorisnike();
            var ispis=
            '<div class="form-horizontal" id="hide">' +
                '<input name="_token" value="'+ aplikacije.token + '" hidden>'+
                (app?'<input id="id_app" name="id" value="'+app['id']+'" hidden>':'')+
                '<div class="form-group">'+
                    '<label class="col-sm-4">Naziv</label>'+
                    '<div class="col-sm-8"><input name="naziv" class="form-control"></div>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label class="col-sm-4">Slug</label>'+
                    '<div class="col-sm-8"><input name="slug" class="form-control" onkeyup="aplikacije.slugCheck(this)"></div>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label class="col-sm-4">Email</label>'+
                    '<div class="col-sm-8"><input name="email" type="email" class="form-control"></div>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label class="col-sm-4">Vlasnik</label>'+
                    '<div class="col-sm-8"><select name="korisnici_id" class="form-control">'+aplikacije.korisnici+'</select></div>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label class="col-sm-4">Napomena<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Napomenu može da vidi samo SuperAdmin"></i></label>'+
                    '<div class="col-sm-8"><textarea name="napomena" class="form-control">'+(app?app.napomena:'')+'</textarea></div>'+
                '</div>'+
                '<div class="form-group">' +
                    '<label class="col-sm-4">Logo</label>' +
                    '<div class="col-sm-8"><img id="imgLogo" onClick="aplikacije.uploadFoto()" style="width:50%;cursor:pointer" src="'+(app?app.logo:'/img/default/logo.jpg')+'" alt="Logo"><input name="logo" value="'+(app?app.logo:'')+'" hidden></div>' +
                '</div>'+
                '<div class="form-group"><label class="col-sm-4"></label><div class="col-sm-8"><button class="btn btn-primary" onclick="aplikacije.sacuvajPodatke()"><i class="glyphicon glyphicon-floppy-disk"></i> Sačuvaj</button></div></div>'+
            '</div>'+
            '<div class="modal fade" id="uploadSlike">' +
                '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                        '<div class="modal-heder">' +
                            '<h2>Izaberite fotografiju korisnika</h2>' +
                        '</div>' +
                        '<div class="modal-body" id="uploadHtmlBody">'+
                            '<input type="file" class="file" name="foto" id="slikaLogo">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div id="wait" style="display:none"><center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center></div>' +
            '<div id="poruka" style="display: none"></div>';
            $('#work-place').html(ispis);
            if(app){
                $('[name=naziv]').val(app.naziv);
                $('[name=slug]').val(app.slug);
                $('[name=email]').val(app.email);
                $('[name=korisnici_id]>option:eq('+app.korisnici_id+')').prop('selected',true);
                $('[name=napomena]').val(app.napomena);
            }else $('[name=korisnici_id]>option:eq(1)').prop('selected',true);
            $('[data-toggle=tooltip]').tooltip();
        },
        slugCheck:function(el){
            $.post('/administracija/aplikacija-slug-check',{_token:aplikacije.token,slug:$(el).val()},function(data){console.log(data);
                $(el).closest('div.form-group').removeClass('has-success has-error').addClass(data==1?'has-success':'has-error');
                $(el).data('check',data);
            })
        },
        sacuvajPodatke:function(){
            if(($('#id_app').val()?true:$('[name=slug]').data('check')==1) && SubmitForm.check('hide'))
                Komunikacija.posalji("/administracija/aplikacija-sacuvaj","hide","poruka","wait","hide",aplikacije.ucitaj());
        },
        edit:function(id){
            var app={id:id,naziv:$('#naziv-'+id).text(),slug:$('#slug-'+id).text(),email:$('#email-'+id).text(),korisnici_id:$('#vlasnik-'+id).val(),logo:$('#logo-'+id).data('src'),napomena:$('#napomena-'+id).text()};
            aplikacije.nova(app);
        },
        status:function(id){
            $('.aktiv-'+id).html('<i class="icon-spin6 animate-spin"></i>');
            $.post('/administracija/aplikacija-deaktiviraj',{_token:'{{csrf_token()}}',id:id,aktivan:$('.aktiv-'+id).data('aktivan')},function(data){
                $('.aktiv-'+id).html(data==1?'<i class="glyphicon glyphicon-ok"></i>':'<i class="glyphicon glyphicon-remove"></i>');
                $('.aktiv-'+id).data('aktivan', data);
            });
        },
        ukloni:function(){
            $('#work-place').html('<h2>Uklanjanje aplikacije za sada nije omogućeno.</h2>');
        },
        uploadFoto:function(){
            $('#slikaLogo').fileinput('clear');
            $("#slikaLogo").fileinput();
            $("#slikaLogo").fileinput('refresh',{
                uploadExtraData: {
                    _token:'{{csrf_token()}}',
                    id:$('#id_app').val(),
                    slug:$('[name=slug]').val()
                },
                uploadUrl: '/administracija/upload-logo',
                uploadAsync: true,
                maxFileCount: 1,
                allowedFileTypes:['image'],
                msgFilesTooMany: 'Broj selektovanih fotografija ({n}) je veći od dozvoljenog ({m}). Pokušajte ponovo!',
                msgInvalidFileType: 'Neispravan tip fajla "{name}". Dozvoljene su samo fotografije.',
                removeLabel: 'Ukloni'
            });
            $('#uploadSlike').modal();

            $('#slikaLogo').on('fileuploaded', function(event, data, previewId, index) {
                var form = data.form, files = data.files, extra = data.extra,
                    response = data.response, reader = data.reader;
                $('#imgLogo').hide();
                $('#imgLogo').attr('src','/'+response+'?'+new Date().getTime());
                $('#imgLogo').fadeIn();
                $('[name=logo]').val('/'+response);
                $('#uploadSlike').modal('hide');
            });
        }
    }
    </script>
@endsection