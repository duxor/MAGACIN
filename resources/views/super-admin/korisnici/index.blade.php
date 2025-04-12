@extends('super-admin.master.osnovni')
@section('content')
    <h2 style="text-align: left" id="proizvodi"><i class="glyphicon glyphicon-user"></i> Korisnici
        <button id="dugmeNovi" class="btn btn-primary" onClick="korisnici.novi()" data-toggle="tooltip" title="Dodaj novog korisnika"><i class="glyphicon glyphicon-plus"></i></button>
        <button id="dugmeUcitaj" class="btn btn-primary" onClick="korisnici.ucitaj()" data-toggle="tooltip" title="Prikaži korisnike" style="display:none"><i class="glyphicon glyphicon-user"></i></button>
        <div class="form-inline" style="float: right">
            <button class="btn btn-sm btn-default" style="padding: 1.5px 10px" data-toggle="tooltip" title="Prikaži sve korisnike" onclick="korisnici.ucitaj()"><i class="icon-users-1"></i></button>
            <div class="form-group">{!!Form::text('pretraga_proizvod',null,['class'=>'form-control','id'=>'pretraga_proizvod'])!!}</div>
            <button class="btn btn-sm btn-default" style="padding: 3px 10px" data-toggle="tooltip" title="Pronađi korisnika" onclick="korisnici.pretrazi()"><i class="glyphicon glyphicon-search"></i></button>
        </div>
    </h2>

    {!! HTML::style('/dragdrop/css/fileinput.css') !!}
    {!! HTML::script('/dragdrop/js/fileinput.min.js') !!}
    <script>
        $(function(){korisnici.ucitaj()})
        var korisnici={
            novi:function(korisnik){
                $('#dugmeNovi').hide();
                $('#dugmeUcitaj').fadeIn();

                $('#work-place').hide();
                $('#work-place').html('' +
                '<style>.fontResize *{font-size: 14px}</style><hr>'+
                '<div id="hide" class="form-horizontal fontResize"style="margin-top: 50px">'+
                    '<div class="col-sm-5">'+
                        '<img id="imgKorisnik" style="width: 100%;margin-bottom:20px;cursor: pointer" onClick="korisnici.uploadFoto()" src="'+(korisnik?korisnik['foto']?korisnik['foto']:'/img/default/slika-korisnika.jpg':'/img/default/slika-korisnika.jpg')+'">' +
                        '<input type="text" name="imgSrc" id="imgSrc" value="'+(korisnik?(korisnik['foto']?korisnik['foto']:''):'')+'" hidden="hidden">'+
                        (korisnik?'':'<input type="text" name="prava_pristupa_id" value="5" hidden="hidden">')+
                        '{!!Form::hidden("_token",csrf_token())!!}' +
                        (korisnik?'<input id="id_korisnika" name="id" value="'+korisnik['id']+'" hidden="hidden">':'')+
                    '</div>'+
                    '<div class="col-sm-offset-1 col-sm-6">'+
                        '<div id="dprezime" class="form-group has-feedback">' +
                            '<label class="col-sm-4">Prezime</label>' +
                            '<div class="col-sm-8">' +
                                '<input name="prezime" value="'+(korisnik?korisnik['prezime']:'')+'" class="form-control" id="prezime">' +
                                '<span id="sprezime" class="glyphicon form-control-feedback"></span>' +
                            '</div>' +
                        '</div>' +
                        '<div id="dime" class="form-group has-feedback">' +
                            '<label class="col-sm-4">Ime</label>' +
                            '<div class="col-sm-8">' +
                                '<input name="ime" value="'+(korisnik?korisnik['ime']:'')+'" class="form-control" id="ime">' +
                                '<span id="sime" class="glyphicon form-control-feedback"></span>' +
                            '</div>' +
                        '</div>' +
                        '<div id="dusername" class="form-group has-feedback">' +
                            '<label class="col-sm-4">Username</label>' +
                            '<div class="col-sm-8">' +
                                '<input name="username" value="'+(korisnik?korisnik['username']:'')+'" class="form-control" id="username">' +
                                '<span id="susername" class="glyphicon form-control-feedback"></span>' +
                            '</div>' +
                        '</div>' +
                        '<div id="dpassword" class="form-group has-feedback">' +
                            '<label class="col-sm-4">Password</label>' +
                            '<div class="col-sm-8">' +
                                '<input type="password" name="password" class="form-control">' +
                                '<span id="susername" class="glyphicon form-control-feedback"></span>' +
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Email</label>' +
                            '<div class="col-sm-8"><input type="email" name="email" value="'+(korisnik?korisnik['email']?korisnik['email']:'':'')+'" class="form-control"></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Telefon</label>' +
                            '<div class="col-sm-8"><input name="telefon" value="'+(korisnik?korisnik['telefon']?korisnik['telefon']:'':'')+'" class="form-control"></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">JMBG</label>' +
                            '<div class="col-sm-8"><input name="jmbg" value="'+(korisnik?korisnik['jmbg']?korisnik['jmbg']:'':'')+'" class="form-control"></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Broj lične karte</label>' +
                            '<div class="col-sm-8"><input name="broj_licne_karte" value="'+(korisnik?korisnik['broj_licne_karte']?korisnik['broj_licne_karte']:'':'')+'" class="form-control"></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Opis <i class="glyphicon glyphicon-exclamation-sign" data-toggle="tooltip" title="Opis je vidljiv samo Vama. Korisnici neće moći da vide kako ste ih opisali."></i></label>' +
                            '<div class="col-sm-8"><textarea name="opis" class="form-control" data-toggle="tooltip" title="Opis je vidljiv samo Vama. Korisnici neće moći da vide kako ste ih opisali.">'+(korisnik?korisnik['opis']?korisnik['opis']:'':'')+'</textarea></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4"></label>' +
                            '<div class="col-sm-8">' +
                                '{!!Form::button("<i class=\'glyphicon glyphicon-floppy-disk\'></i> Sačuvaj",["class"=>"btn btn-primary","onclick"=>"korisnici.sacuvajPodatke()"])!!}' +
                                '{!!Form::button("<i class=\'glyphicon glyphicon-trash\'></i> Otkaži",["class"=>"btn btn-danger","data-dismiss"=>"modal","onclick"=>"korisnici.ucitaj()"])!!}' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'+
                '<div class="modal fade" id="uploadSlike">' +
                    '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                            '<div class="modal-heder">' +
                                '<h2>Izaberite fotografiju korisnika</h2>' +
                            '</div>' +
                            '<div class="modal-body" id="uploadHtmlBody">'+
                                '<input type="file" class="file" name="foto" id="slikaKorisnika">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div id="wait" style="display:none"><center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center></div>' +
                '<div id="poruka" style="display: none"></div>');
                $('#work-place').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            },
            ucitaj:function(pretraga){
                $('#dugmeUcitaj').hide();
                $('#dugmeNovi').fadeIn();
                $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%;margin-top:80px"></i></center>');
                $.post('/administracija/korisnici/ucitaj',{
                            _token:'{{csrf_token()}}',
                            pretraga:pretraga
                        },
                        function(data){
                            var korisnici=JSON.parse(data);
                            if(korisnici.length<1){
                                $('#work-place').html('<h3>Ne postoji ni jedan korisnik u evidenciji.');
                                return;
                            }
                            var ispis='' +
                                    '<table class="table table-striped">' +
                                        '<thead>' +
                                            '<tr><th></th><th>Prezime i Ime</th><th>Email</th><th>Telefon</th><th>JMBG</th><th>Broj lične karte</th><th>Aktivan</th><th></th></tr>' +
                                        '</thead>' +
                                    '<tbody>';
                            for(var i=0;i<korisnici.length;i++){
                                ispis+=
                                '<tr>' +
                                    '<td><button class="btn btn-xs btn-info" data-toggle="tooltip" data-template=\'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:rgba(0,0,0,0)"></div></div>\' data-placement="bottom" data-html="true" title="<img src=\''+korisnici[i]['foto']+'\'>"><i class="glyphicon glyphicon-picture"></i></button></td>'+
                                    '<td>'+korisnici[i]['prezime']+' '+korisnici[i]['ime']+'</td>' +
                                    '<td>'+korisnici[i]['email']+'</td>' +
                                    '<td>'+(korisnici[i]['telefon']?korisnici[i]['telefon']:'')+'</td>' +
                                    '<td>'+(korisnici[i]['jmbg']?korisnici[i]['jmbg']:'')+'</td>' +
                                    '<td>'+(korisnici[i]['broj_licne_karte']?korisnici[i]['broj_licne_karte']:'')+'</td>' +
                                    '<td class="aktiv-'+korisnici[i]['id']+'" data-aktivan="'+korisnici[i]['aktivan']+'">'+(korisnici[i]['aktivan']?'<i class="glyphicon glyphicon-ok"></i>':'<i class="glyphicon glyphicon-remove"></i>')+'</td>' +
                                    '<td>' +
                                        '<a href="#" class="btn btn-xs btn-info" data-toggle="tooltip" title="Ažuriraj" onclick="korisnici.edit('+korisnici[i]['id']+')" style="margin-right:5px"><span class="glyphicon glyphicon-pencil"></span></a>' +
                                        '<a href="#" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Promjena statusa (ne)aktivan" onclick="korisnici.status('+korisnici[i]['id']+')" style="margin-right:5px"><span class="glyphicon glyphicon-lock"></span></a>' +
                                        '<a href="#" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Ukloni korisnika" onclick="korisnici.pripremiZaBrisanje('+korisnici[i]['id']+',\''+korisnici[i]['prezime']+' '+korisnici[i]['ime']+'\')"><span class="glyphicon glyphicon-trash"></span></a>' +
                                    '</td>' +
                                '</tr>';
                            }
                            $('#work-place').html(ispis+'</tbody></table>');
                            $('[data-toggle=tooltip]').tooltip();
                        });
            },
            edit:function(korisnik){
                $('#dugmeNovi').hide();
                $('#dugmeUcitaj').fadeIn();
                $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%;margin-top:80px"></i></center>');
                $.post('/administracija/korisnici/edit-ucitaj',
                        {
                            _token: '{{csrf_token()}}',
                            id: korisnik
                        }, function (data) {
                            korisnici.novi(JSON.parse(data))
                        });
            },
            status:function ststusKorisnika(korisnik){
                $('.aktiv-'+korisnik).html('<i class="icon-spin6 animate-spin"></i>');
                $.post('/administracija/korisnici/deaktiviraj',
                        {
                            _token: '{{csrf_token()}}',
                            id: korisnik,
                            aktivan:$('.aktiv-'+korisnik).data('aktivan')
                        }, function (data) {
                            $('.aktiv-'+korisnik).html(data==1?'<i class="glyphicon glyphicon-ok"></i>':'<i class="glyphicon glyphicon-remove"></i>');
                            $('.aktiv-'+korisnik).data('aktivan', data);
                        });
            },
            pretrazi:function(){
                korisnici.ucitaj($('#pretraga_proizvod').val());
            },
            sacuvajPodatke:function(){
                if(SubmitForm.check('hide'))
                    Komunikacija.posalji("/administracija/korisnici/azuriraj","hide","poruka","wait","hide");
            },
            uploadFoto:function(){
                $('#slikaKorisnika').fileinput('clear');
                $("#slikaKorisnika").fileinput();
                $("#slikaKorisnika").fileinput('refresh',{
                    uploadExtraData: {
                        _token:'{{csrf_token()}}',
                        id:$('#id_korisnika').val()
                    },
                    uploadUrl: '/administracija/korisnici/upload-foto',
                    uploadAsync: true,
                    maxFileCount: 1,
                    allowedFileTypes:['image'],
                    msgFilesTooMany: 'Broj selektovanih fotografija ({n}) je veći od dozvoljenog ({m}). Pokušajte ponovo!',
                    msgInvalidFileType: 'Neispravan tip fajla "{name}". Dozvoljene su samo fotografije.',
                    removeLabel: 'Ukloni'
                });
                $('#uploadSlike').modal();

                $('#slikaKorisnika').on('fileuploaded', function(event, data, previewId, index) {
                    var form = data.form, files = data.files, extra = data.extra,
                            response = data.response, reader = data.reader;
                    $('#imgKorisnik').hide();
                    $('#imgKorisnik').attr('src','/'+response+'?'+new Date().getTime());
                    $('#imgKorisnik').fadeIn();
                    $('#imgSrc').val('/'+response);
                    $('#uploadSlike').modal('hide');
                });
            },
            pripremiZaBrisanje:function(id,prezimeIme){
                $('#modUnBo').html('<h2>Da li ste sigurni da želite da uklonite korisnika '+prezimeIme+' sa svim podacima vezanim za njega.<br><b>Napomena: Uklonjeni podaci nemaju mogućnost oporavka.</b></h2>');
                $('#modUnFoBtn').html('<i class="glyphicon glyphicon-trash"></i> Ukloni').attr('onclick','korisnici.ukloni('+id+')');
                $('#modalUniverzalni').modal();
            },
            ukloni:function(id){
                $('#modalUniverzalni').modal('hide');
            }
        }
    </script>

    <div id="work-place"></div>
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
@endsection