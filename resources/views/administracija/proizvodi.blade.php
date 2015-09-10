@extends('administracija.master')
@section('content')

    @if(isset($proizvodi))
    @if($proizvodi)
        <h2 style="text-align: left" id="proizvodi"><i class="glyphicon glyphicon-qrcode"></i> Proizvodi
            <button class="btn btn-primary" data-toggle="modal" data-target="#noviProizvod"><i class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Dodaj novi proizvod"></i></button>
            <div class="form-inline" style="float: right">
                <button class="btn btn-sm btn-default" data-toggle="tooltip" title="Pronađi proizvod"><i class="glyphicon glyphicon-search"></i></button>
                <div class="form-group">{!!Form::text('pretraga_proizvod',null,['class'=>'form-control'])!!}</div>
                <div class="form-group">{!!Form::select('pretraga_vrsta_proizvoda',array_merge([0=>'Svi proizvodi'],$vrstaProizvoda),0,['class'=>'form-control'])!!}</div>
            </div>
        </h2>
        <div id="work-place">
            <table class="table table-striped">
                <thead>
                <tr><th>Šifra</th><th>Naziv</th><th>Opis</th><th></th><th></th><th></th></tr>
                </thead>
                <tbody>
                @foreach($proizvodi as $p)
                    <tr>
                        <td><a href="/administracija/proizvod/azuriraj/{{$p['id']}}">{{$p['sifra']}}</a></td>
                        <td>{{$p['naziv']}}</td>
                        <td>{{$p['opis']}}</td>
                        <td><a href="/administracija/proizvod/azuriraj/{{$p['id']}}" class="btn btn-lg btn-info" data-toggle="tooltip" title="Ažuriraj"><span class="glyphicon glyphicon-pencil"></span></a></td>
                        <td><a href="/administracija/proizvod/magacin/{{$p['id']}}" class="btn btn-lg btn-primary" data-toggle="tooltip" title="Dodaj u magacin"><span class="glyphicon glyphicon-log-in"></span></a></td>
                        <td><a href="/administracija/proizvod/ukloni/{{$p['id']}}" class="btn btn-lg btn-danger" data-toggle="tooltip" title="Ukloni"><span class="glyphicon glyphicon-trash"></span></a></td>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Ni jedan proizvod nije dodat u evidenciju.</p>
    @endif
    @endif

    @if(isset($novi) or isset($proizvod))
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
    @endif

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
@endsection