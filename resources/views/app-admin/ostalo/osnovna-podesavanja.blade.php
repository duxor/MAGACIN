@extends('app-admin.master.prazan')
@section('content')
    <div class="col-sm-4">
        <div class="list-group">
          <a href="#" class="list-group-item" data-target="nalog">Nalog</a>
          <a href="#" class="list-group-item" data-target="podaci">Podaci</a>
          <a href="#" class="list-group-item" data-target="fakture">Faktura</a>
          <a href="#" class="list-group-item" data-target="sifarnici">Pozicija zaposlenog</a>
          <a href="#" class="list-group-item" data-target="sifarnici">Šifarnici</a>
        </div>
    </div>

    <div class="col-sm-8" id="poruka" style="display:none"></div>
    <div class="col-sm-8" id="wait"></div>
    <div id="work-place" class="col-sm-8">Osnovna podešavanja platforme.</div>
    {!! HTML::style('/dragdrop/css/fileinput.css') !!}
    {!! HTML::script('/dragdrop/js/fileinput.min.js') !!}
    <script>
        $('a.list-group-item').click(function(){
            $('a.list-group-item').removeClass('active');
            $(this).addClass('active');
            nav.akcija($(this).data('target'));
        });
        var nav={
            token:'{{csrf_token()}}',
            akcija:function(t){
                switch(t){
                    case 'nalog': nav.nalog(); break;
                    case 'podaci': nav.podaci(); break;
                    case 'fakture': nav.fakture(); break;
                    case 'sifarnici': nav.sifarnici(); break;
                }
            },
            nalog:function(){
                var ispis='<h2>Nalog</h2><hr>';
                $.post('/administracija/osnovno-nalog',{_token:nav.token},function(data){
                    var rezultat=JSON.parse(data);
                    ispis=
                    '<div class="form-horizontal" id="hide">' +
                        '<input name="_token" value="'+nav.token+'" hidden>'+
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Logo</label>' +
                            '<div class="col-sm-8">' +
                                '<a href="#" class="thumbnail" onclick="nav.uploadFoto()"><img id="imgLogo" src="'+rezultat.logo+'" style="width:100%" alt="Logo"></a>' +
                                '<input name="logo" hidden>'+
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Naziv</label>' +
                            '<div class="col-sm-8">' +
                                '<input class="form-control" name="naziv">' +
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Adresa</label>' +
                            '<div class="col-sm-8">' +
                                '<input class="form-control" name="adresa">' +
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Grad</label>' +
                            '<div class="col-sm-8">' +
                                '<input class="form-control" name="grad">' +
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-4">Telefon</label>' +
                            '<div class="col-sm-8">' +
                                '<input class="form-control" name="telefon">' +
                            '</div>' +
                        '</div>' +
                        '<label class="col-sm-4"></label><div class="col-sm-8"><button class="btn btn-primary" onclick="nav.sacuvajPodatke(\'nalog\')"><i class="glyphicon glyphicon-floppy-disk"></i> Sačuvaj</button></div>' +
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
                    '</div>';
                    $('#work-place').html(ispis);
                    $('input[name=naziv]').val(rezultat.naziv);
                    $('input[name=adresa]').val(rezultat.adresa);
                    $('input[name=grad]').val(rezultat.grad);
                    $('input[name=telefon]').val(rezultat.telefon);
                })
            },
            podaci:function(){
                var ispis='<h2>Podaci</h2><hr>';
                $.post('/administracija/osnovno-podaci',{_token:nav.token},function(data){
                    var rezultat=JSON.parse(data);
                    ispis=
                        '<div id="hide" class="form-horizontal">' +
                            '<input name="_token" value="'+nav.token+'" hidden>'+
                            '<div class="form-group">' +
                                '<label class="col-sm-4">JIB</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="jib">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">PIB</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="pib">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">PDV</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="pdv">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">Žiro račun 1</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="ziro_racun_1">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">Banka 1</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="banka_1">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">Žiro račun 2</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="ziro_racun_2">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">Banka 2</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="banka_2">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">Registracija</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="registracija">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<label class="col-sm-4">Broj upisa</label>' +
                                '<div class="col-sm-8">' +
                                    '<input class="form-control" name="broj_upisa">' +
                                '</div>' +
                            '</div>' +
                            '<button class="btn btn-primary" onclick="nav.sacuvajPodatke(\'podaci\')"><i class="glyphicon glyphicon-floppy-disk"></i> Sačuvaj</button>' +
                        '</div>';
                    $('#work-place').html(ispis);
                    $('input[name=jib]').val(rezultat.jib);
                    $('input[name=pib]').val(rezultat.pib);
                    $('input[name=pdv]').val(rezultat.pdv);
                    $('input[name=ziro_racun_1]').val(rezultat.ziro_racun_1);
                    $('input[name=banka_1]').val(rezultat.banka_1);
                    $('input[name=ziro_racun_2]').val(rezultat.ziro_racun_2);
                    $('input[name=banka_2]').val(rezultat.banka_2);
                    $('input[name=registracija]').val(rezultat.registracija);
                    $('input[name=broj_upisa]').val(rezultat.broj_upisa);
                })
            },
            fakture:function(){
                var ispis='<h2>Fakture</h2><hr>';
                $.post('/administracija/osnovno-fakture',{_token:nav.token},function(data){
                    var rezultat=JSON.parse(data);
                    ispis=
                    '<div id="hide" class="form-vertical">' +
                        '<input name="_token" value="'+nav.token+'" hidden>'+
                        '<div class="form-group">' +
                            '<label>Futer fakture 1</label>' +
                            '<div>' +
                                '<textarea class="form-control" name="faktura_futer_1"></textarea>' +
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label>Futer fakture 2</label>' +
                            '<div>' +
                                '<textarea class="form-control" name="faktura_futer_2"></textarea>' +
                            '</div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label>Futer fakture 3</label>' +
                            '<div>' +
                                '<textarea class="form-control" name="faktura_futer_3"></textarea>' +
                            '</div>' +
                        '</div>' +
                        '<button class="btn btn-primary" onclick="nav.sacuvajPodatke(\'fakture\')"><i class="glyphicon glyphicon-floppy-disk"></i> Sačuvaj</button>' +
                    '</div>';
                    $('#work-place').html(ispis);
                    $('textarea[name=faktura_futer_1]').val(rezultat.faktura_futer_1);
                    $('textarea[name=faktura_futer_2]').val(rezultat.faktura_futer_2);
                    $('textarea[name=faktura_futer_3]').val(rezultat.faktura_futer_3);
                })
            },
            sifarnici:function(){
                var ispis='<h2>Šifarnici</h2><hr>';
                $.post('/administracija/osnovno-sifarnici',{_token:nav.token},function(data){
                    var rezultat=JSON.parse(data);
                    ispis+='U pripremi je administracija sledećih šifarnika: vrsta_proizvoda; ';
                    $('#work-place').html(ispis);
                })
            },
            sacuvajPodatke:function(akcija){
                Komunikacija.posalji("/administracija/aplikacija-osnovno-sacuvaj","hide","poruka","wait");
                nav.akcija(akcija);
            },
            uploadFoto:function(){
                $('#slikaLogo').fileinput('clear');
                $("#slikaLogo").fileinput();
                $("#slikaLogo").fileinput('refresh',{
                    uploadExtraData: {
                        _token:'{{csrf_token()}}'
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