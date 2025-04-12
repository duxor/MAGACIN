@extends('radnik.master.osnovni')
@section('content')

        <h2 style="text-align: left" id="proizvodi"><i class="glyphicon glyphicon-qrcode"></i> Proizvodi
            <button id="dugmeNovi" class="btn btn-primary" onClick="proizvod.novi()" data-toggle="tooltip" title="Dodaj novi proizvod"><i class="glyphicon glyphicon-plus"></i></button>
            <button id="dugmeUcitaj" class="btn btn-primary" onClick="proizvod.ucitaj()" data-toggle="tooltip" title="Prikaži proizvode" style="display:none"><i class="glyphicon glyphicon-qrcode"></i></button>
            <div class="form-inline" style="float: right">
                <button class="btn btn-sm btn-default" data-toggle="tooltip" title="Pronađi proizvod" onclick="proizvod.pretrazi()"><i class="glyphicon glyphicon-search"></i></button>
                <div class="form-group">{!!Form::text('pretraga_proizvod',null,['class'=>'form-control','id'=>'pretraga_proizvod'])!!}</div>
                <div class="form-group">{!!Form::select('pretraga_vrsta_proizvoda',array_merge([0=>'Svi proizvodi'],$vrstaProizvoda),0,['class'=>'form-control'])!!}</div>
            </div>
        </h2>
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
        {!! HTML::style('/dragdrop/css/fileinput.css') !!}
        {!! HTML::script('/dragdrop/js/fileinput.min.js') !!}
        <script>
            $(function(){proizvod.ucitaj()})
            var proizvod={
                token:'{{csrf_token()}}',
                ucitaj:function(pretraga,vrsta){
                    $('#dugmeUcitaj').hide();
                    $('#dugmeNovi').fadeIn();
                    $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%;margin-top:80px"></i></center>');
                    $.post('/administracija/proizvod',
                    {
                        _token:proizvod.token,
                        pretraga:pretraga,
                        vrsta:vrsta
                    },function(data){
                        var proizvodi=JSON.parse(data);
                        if(proizvodi.length<1){
                            $('#work-place').html('<h3>Ne postoji ni jedan proizvod u evidenciji.');
                            return;
                        }
                        var ispis='' +
                            '<table class="table table-striped table-hover table-condensed">' +
                                '<thead>' +
                                    '<tr><th></th><th>Šifra</th><th>Naziv</th><th></th></tr>' +
                                '</thead>' +
                            '<tbody>';
                            for(var i=0;i<proizvodi.length;i++){
                                ispis+='<tr>' +
                                    '<td><button class="btn btn-xs btn-info" data-toggle="tooltip" data-template=\'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background-color:rgba(0,0,0,0)"></div></div>\' data-placement="bottom" data-html="true" title="<img style=\'width:100%\' src=\''+proizvodi[i]['foto']+'\'>"><i class="glyphicon glyphicon-picture"></i></button></td>'+
                                    '<td><a href="/administracija/proizvod/azuriraj/'+proizvodi[i]['id']+'">'+proizvodi[i]['sifra']+'</a></td>' +
                                    '<td>'+proizvodi[i]['naziv']+'</td>' +
                                    '<td>' +
                                        '<a href="#" class="btn btn-xs btn-info" style="margin-right:5px" data-toggle="tooltip" title="Ažuriraj" onclick="proizvod.edit('+proizvodi[i]['id']+')"><span class="glyphicon glyphicon-pencil"></span></a>' +
                                        '<a style="margin-right:5px" onclick="korpa.dodaj(this,'+proizvodi[i]['id']+')"  class="btn btn-xs btn-primary" data-toggle="tooltip" title="Dodaj u korpu"><span class="glyphicon glyphicon-log-in"></span></a>' +
                                        '<a onclick="proizvod.pripremiZaBrisanje('+proizvodi[i]['id']+',\''+proizvodi[i]['naziv']+' ('+proizvodi[i]['sifra']+')\')" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Ukloni"><span class="glyphicon glyphicon-trash"></span></a>' +
                                    '</td>' +
                                    '</td>' +
                                    '</tr>';
                            }
                        $('#work-place').html(ispis+'</tbody></table>');
                        $('[data-toggle=tooltip]').tooltip();
                    });
                },
                edit:function(id){
                    $('#dugmeNovi').hide();
                    $('#dugmeUcitaj').fadeIn();
                    $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%;margin-top:80px"></i></center>');
                    $.post('/administracija/proizvod/edit-ucitaj',{_token:proizvod.token,id:id},function(data){
                        proizvod.novi(JSON.parse(data))
                    });
                },
                novi:function(_proizvod){
                    $('#dugmeNovi').hide();
                    $('#dugmeUcitaj').fadeIn();

                    $('#work-place').hide();
                    $('#work-place').html('' +
                    '<hr><div class="form-horizontal" id="hide" style="margin-top: 50px">'+
                        '<input name="_token" value="'+proizvod.token+'" hidden="hidden">'+
                        (_proizvod?'<input id="id_proizvoda" name="id" value="'+_proizvod['id']+'" hidden="hidden">':'')+
                        '<style>.fontResize *{font-size: 12px}</style>'+
                        '<div class="col-sm-4 fontResize">' +
                            '<img id="imgProizvod" style="width: 100%;margin-bottom:20px;cursor: pointer" onClick="proizvod.uploadFoto()" src="'+(_proizvod?_proizvod['foto']?_proizvod['foto']:'/img/default/slika-proizvoda.jpg':'/img/default/slika-proizvoda.jpg')+'">' +
                            '<input type="text" name="imgSrc" id="imgSrc" value="'+(_proizvod?_proizvod['foto']:'')+'" hidden="hidden">'+
                            '<div class="form-group">' +
                                '<div class="col-sm-12">' +
                                    '{!!Form::select("vrsta_proizvoda_id",$vrstaProizvoda,0,["class"=>"form-control","data-toggle"=>"tooltip","title"=>"Vrsta proizvoda"])!!}'+
                                '</div>' +
                            '</div>' +
                            '<div id="dssifra" class="form-group has-feedback">' +
                                '<div class="col-sm-12">' +
                                    '<input name="ssifra" value="'+(_proizvod?_proizvod['sifra']:'')+'" placeholder="Šifra" data-toggle="tooltip" title="Šifra proizvoda" class="form-control" id="ssifra">' +
                                    '<span id="sssifra" class="glyphicon form-control-feedback"></span>' +
                                '</div>' +
                            '</div>' +
                            '<div id="dnaziv" class="form-group has-feedback">' +
                                '<div class="col-sm-12">' +
                                    '<input name="naziv" data-toggle="tooltip" title="Naziv proizvoda" value="'+(_proizvod?_proizvod['naziv']:'')+'" placeholder="Naziv" class="form-control" id="naziv">' +
                                    '<span id="snaziv" class="glyphicon form-control-feedback"></span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-7 fontResize">' +
                            '<div class="form-group">' +
                                '<div class="col-sm-offset-3 col-sm-9">' +
                                    '<input data-toggle="tooltip" title="Bar kod proizvoda" name="bar_kod" value="'+(_proizvod?_proizvod['bar_kod']:'')+'" placeholder="Bar kod" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<div class="col-sm-offset-3 col-sm-9">' +
                                    '<input name="proizvodjac" data-toggle="tooltip" title="Proizvođač proizvoda" value="'+(_proizvod?_proizvod['proizvodjac']:'')+'" placeholder="Proizvođač" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<div class="col-sm-offset-3 col-sm-9">' +
                                    '<input name="jedinica_mjere" data-toggle="tooltip" title="Jedinica mjere" value="'+(_proizvod?_proizvod['jedinica_mjere']:'')+'" placeholder="Jedinica mjere" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<div class="col-sm-offset-3 col-sm-9">' +
                                    '<input name="pakovanje_jedinica_mjere" data-toggle="tooltip" title="Pakovanje jedinica mjere" value="'+(_proizvod?_proizvod['pakovanje_jedinica_mjere']:'')+'" placeholder="Pakovanje jedinica mjere" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<div class="col-sm-offset-3 col-sm-9">' +
                                    '<input name="pakovanje_kolicina" data-toggle="tooltip" title="Količina proizvoda u pakovanju" value="'+(_proizvod?_proizvod['pakovanje_kolicina']:'')+'" placeholder="Pakovanje kolicina" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div id="dopis" class="form-group has-feedback">' +
                                '<div class="col-sm-offset-3 col-sm-9 ">' +
                                    '<textarea name="opis" placeholder="Opis" data-toggle="tooltip" title="Opis proizvoda" class="form-control" id="opis">'+(_proizvod?_proizvod['opis']:'')+'</textarea>' +
                                    '<span id="sopis" class="glyphicon form-control-feedback"></span>' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                                '<div class="col-sm-3"></div>' +
                                '<div class="col-sm-9">' +
                                    '<button class="btn btn-lg btn-primary" onClick="proizvod.sacuvajPodatke()" type="button" style="margin-right: 10px"><span class="glyphicon glyphicon-floppy-disk"></span> Sačuvaj</button>' +
                                    '<button class="btn btn-lg btn-danger" onclick="proizvod.ucitaj()"><span class="glyphicon glyphicon-trash"></span> Otkaži</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="modal fade" id="uploadSlike">' +
                        '<div class="modal-dialog">' +
                            '<div class="modal-content">' +
                                '<div class="modal-heder">' +
                                    '<h2>Izaberite fotografiju proizvoda</h2>' +
                                '</div>' +
                                '<div class="modal-body" id="uploadHtmlBody">'+
                                    '<input type="file" class="file" name="foto" id="slikaProizoda">' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div id="wait" style="display:none"><center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center></div>' +
                    '<div id="poruka" style="display: none"></div>');
                    $('#work-place').fadeIn();
                    $('[data-toggle=tooltip]').tooltip();
                },
                pripremiZaBrisanje:function(id,nazivSifra){
                    $('#modUnBo').html('<h2>Da li ste sigurni da želite da uklonite proizvod '+nazivSifra+' sa svim podacima vezanim za njega.<br><b>Napomena: Uklonjeni podaci nemaju mogućnost oporavka.</b></h2>');
                    $('#modUnFoBtn').html('<i class="glyphicon glyphicon-trash"></i> Ukloni').attr('onclick','proizvod.ukloni('+id+')');
                    $('#modalUniverzalni').modal();
                },
                ukloni:function(id){
                    $('#modUnBo').html('Brisanje noje omogućeno');
                },
                sacuvajPodatke:function(){
                    if(SubmitForm.check('hide'))
                        Komunikacija.posalji("/administracija/proizvod/edit-save","hide","poruka","wait","hide");
                },
                pretrazi:function(){
                    proizvod.ucitaj($('#pretraga_proizvod').val(),$('[name=pretraga_vrsta_proizvoda]').val());
                },
                uploadFoto:function(){
                    $('#slikaProizoda').fileinput('clear');
                    $("#slikaProizoda").fileinput();
                    $("#slikaProizoda").fileinput('refresh',{
                        uploadExtraData: {
                            _token:proizvod.token,
                            id:$('#id_proizvoda').val()
                        },
                        uploadUrl: '/administracija/proizvod/upload-foto',
                        uploadAsync: true,
                        maxFileCount: 1,
                        allowedFileTypes:['image'],
                        msgFilesTooMany: 'Broj selektovanih fotografija ({n}) je veći od dozvoljenog ({m}). Pokušajte ponovo!',
                        msgInvalidFileType: 'Neispravan tip fajla "{name}". Dozvoljene su samo fotografije.',
                        removeLabel: 'Ukloni'
                    });
                    $('#uploadSlike').modal();

                    $('#slikaProizoda').on('fileuploaded', function(event, data, previewId, index) {
                        var form = data.form, files = data.files, extra = data.extra,
                            response = data.response, reader = data.reader;
                        $('#imgProizvod').attr('src','/'+response);
                        $('#imgSrc').val('/'+response);console.log($('#imgSrc').val());
                        $('#uploadSlike').modal('hide');
                    });
                }
            }
        </script>
        <div id="work-place">

        </div>

    {{--@if(isset($novi) or isset($proizvod))
        {!! Form::open(['url'=>'/administracija/proizvod/proizvod','class'=>'form-horizontal','id'=>'forma']) !!}
        @if(!isset($proizvod)) {!!$proizvod = null!!} @else {!!Form::hidden('id',$proizvod['id'])!!} @endif
        <style>.fontResize *{font-size: 12px}</style>
        <div class="col-sm-4 fontResize">
            <img style="width: 100%;margin-bottom:20px" src="/img/default/slika-proizvoda.jpg">
            {!! Form::text('vrsta_proizvoda_id',$proizvod['vrsta_proizvoda_id'],['class'=>'form-control']) !!}
            <div id="dssifra" class="has-feedback">
                {!! Form::text('ssifra',$proizvod['sifra'],['placeholder'=>'Šifra','class'=>'form-control','id'=>'ssifra']) !!}
                <span id="sssifra" class="glyphicon form-control-feedback"></span>
            </div>
            <div id="dnaziv" class="has-feedback">
                {!! Form::text('naziv',$proizvod['naziv'],['placeholder'=>'Naziv','class'=>'form-control','id'=>'naziv']) !!}
                <span id="snaziv" class="glyphicon form-control-feedback"></span>
            </div>
            <div id="dopis" class="has-feedback">
                {!! Form::textarea('opis',$proizvod['opis'],['placeholder'=>'Opis','class'=>'form-control','id'=>'opis']) !!}
                <span id="sopis" class="glyphicon form-control-feedback"></span>
            </div>
        </div>
        <div class="col-sm-7 fontResize">
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    {!! Form::text('bar_kod',$proizvod['bar_kod'],['placeholder'=>'Bar kod','class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    {!! Form::text('proizvodjac',$proizvod['proizvodjac'],['placeholder'=>'Proizvođač','class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    {!! Form::text('jedinica_mjere',$proizvod['jedinica_mjere'],['placeholder'=>'Jedinica mjere','class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    {!! Form::text('pakovanje_jedinica_mjere',$proizvod['pakovanje_jedinica_mjere'],['placeholder'=>'Pakovanje jedinica mjere','class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    {!! Form::text('pakovanje_kolicina',$proizvod['pakovanje_kolicina'],['placeholder'=>'Pakovanje kolicina','class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    {!! Form::button('<span class="glyphicon glyphicon-play-circle"></span> Sačuvaj', ['class' => 'btn btn-lg btn-primary','onClick'=>'SubmitForm.submit(\'forma\')']) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-refresh"></span> Resetuj unos', ['class' => 'btn btn-lg btn-warning','type'=>'reset']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endif--}}

    @if(isset($umagacin))
        {!! Form::open(['url'=>'/administracija/proizvod/magacin','class'=>'form-horizontal','id'=>'forma']) !!}
            <div class="form-group">
                {!! Form::label('lsifra','Šifra proizvoda',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('sifra',$proizvod_podaci['sifra'],['placeholder'=>'Šifra','class'=>'form-control', 'disabled']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('lnaziv','Naziv proizvoda',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('naziv',$proizvod_podaci['naziv'],['placeholder'=>'Naziv','class'=>'form-control', 'disabled']) !!}
                </div>
            </div>
            {!!Form::hidden('proizvod_id',$proizvod_podaci['id'])!!}

            <div class="form-group">
                {!! Form::label('lmagacinid_id','Magacin',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::select('magacinid_id',$umagacin,null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div id="dkolicina_stanje" class="form-group has-feedback">
                {!! Form::label('lkolicina_stanje','Količina stanje',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('kolicina_stanje',1,['placeholder'=>'Količina stanje','class'=>'form-control']) !!}
                    <span id="skolicina_stanje" class="glyphicon form-control-feedback"></span>
                </div>
            </div>
            <div id="dkolicina_min" class="form-group has-feedback">
                {!! Form::label('lkolicina_min','Količina minimum',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('kolicina_min',1,['placeholder'=>'Količina minimum','class'=>'form-control']) !!}
                    <span id="skolicina_min" class="glyphicon form-control-feedback"></span>
                </div>
            </div>
            <hr>
            <div id="dstolaza" class="form-group has-feedback">
                {!! Form::label('lkolicina_min','Stolaza',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('stolaza',1,['placeholder'=>'Stolaza','class'=>'form-control']) !!}
                    <span id="sstolaza" class="glyphicon form-control-feedback"></span>
                </div>
            </div>
            <div id="dpolica" class="form-group has-feedback">
                {!! Form::label('lpolica','Polica',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('polica',1,['placeholder'=>'Količina minimum','class'=>'form-control']) !!}
                    <span id="spolica" class="glyphicon form-control-feedback"></span>
                </div>
            </div>
            <div id="dpozicija" class="form-group has-feedback">
                {!! Form::label('lpozicija','Pozicija na polici',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::text('pozicija',1,['placeholder'=>'Pozicija','class'=>'form-control']) !!}
                    <span id="spozicija" class="glyphicon form-control-feedback"></span>
                </div>
            </div>
            <div id="dopis" class="form-group has-feedback">
                {!! Form::label('lopis','Opis pozicije',['class'=>'control-label col-sm-3']) !!}
                <div class="col-sm-9">
                    {!! Form::textarea('opis',null,['placeholder'=>'Opis pozicije','class'=>'form-control']) !!}
                    <span id="sopis" class="glyphicon form-control-feedback"></span>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    {!! Form::button('<span class="glyphicon glyphicon-play-circle"></span> Sačuvaj', ['class' => 'btn btn-lg btn-primary','onClick'=>'SubmitForma.submit(\'forma\')']) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-refresh"></span> Obriši unos', ['class' => 'btn btn-lg btn-warning','type'=>'reset']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    @endif
    <i class='icon-spin6 animate-spin' style="font-size: 1px;color:rgba(0,0,0,0)"></i>
@endsection