@extends('masterBackEnd')
@section('content')

    @if(isset($proizvodi))
    @if($proizvodi)
        <table class="table table-striped">
            <thead>
                <tr><th>Šifra</th><th>Naziv</th><th>Opis</th><th>Cijena</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($proizvodi as $p)
                    <tr>
                        <td><a href="/administracija/proizvod/azuriraj/{{$p['id']}}">{{$p['sifra']}}</a></td>
                        <td>{{$p['naziv']}}</td>
                        <td>{{$p['opis']}}</td>
                        <td>{{$p['cijena']}}</td>
                        <td>
                            <a href="/administracija/proizvod/azuriraj/{{$p['id']}}" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-pencil"></span> Ažuriraj</a>
                            <a href="/administracija/proizvod/magacin/{{$p['id']}}" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-log-in"></span> Dodaj u magacin</a>
                            <a href="/administracija/proizvod/ukloni/{{$p['id']}}" class="btn btn-lg btn-danger"><span class="glyphicon glyphicon-trash"></span> Ukloni</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Ni jedan proizvod nije dodat u evidenciju.</p>
    @endif
    @endif

    @if(isset($novi) or isset($proizvod))
        {!! Form::open(['url'=>'/administracija/proizvod/proizvod','class'=>'form-horizontal','id'=>'forma']) !!}
        @if(!isset($proizvod))
            {!!$proizvod = null!!}
        @else
            {!!Form::hidden('id',$proizvod['id'])!!}
        @endif
        <div id="dsifra" class="form-group has-feedback">
            {!! Form::label('lsifra','Šifra',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::text('sifra',$proizvod['sifra'],['placeholder'=>'Šifra','class'=>'form-control','id'=>'sifra']) !!}
                <span id="ssifra" class="glyphicon form-control-feedback"></span>
            </div>
        </div>
        <div id="dnaziv" class="form-group has-feedback">
            {!! Form::label('lnaziv','Naziv',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::text('naziv',$proizvod['naziv'],['placeholder'=>'Naziv','class'=>'form-control','id'=>'naziv']) !!}
                <span id="snaziv" class="glyphicon form-control-feedback"></span>
            </div>
        </div>
        <div id="dopis" class="form-group has-feedback">
            {!! Form::label('lopis','Opis',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::textarea('opis',$proizvod['opis'],['placeholder'=>'Opis','class'=>'form-control','id'=>'opis']) !!}
                <span id="sopis" class="glyphicon form-control-feedback"></span>
            </div>
        </div>
        <div id="dcijena" class="form-group has-feedback">
            {!! Form::label('lcijena','Naziv',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::text('cijena',$proizvod['cijena'],['placeholder'=>'Cijena','class'=>'form-control','id'=>'cijena']) !!}
                <span id="scijena" class="glyphicon form-control-feedback"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                {!! Form::button('<span class="glyphicon glyphicon-play-circle"></span> Sačuvaj', ['class' => 'btn btn-lg btn-primary','onClick'=>'SubmitForma.submit(\'forma\')']) !!}
                {!! Form::button('<span class="glyphicon glyphicon-refresh"></span> Resetuj unos', ['class' => 'btn btn-lg btn-warning','type'=>'reset']) !!}
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
                    {!! Form::button('<span class="glyphicon glyphicon-refresh"></span> Resetuj unos', ['class' => 'btn btn-lg btn-warning','type'=>'reset']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    @endif
@endsection