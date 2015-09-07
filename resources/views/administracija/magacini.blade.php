@extends('administracija.master')
@section('content')

    @if(isset($magacini))
    @if($magacini)
        <table class="table table-striped">
            <thead>
                <tr><th></th><th>Naziv</th><th>Opis</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($magacini as $m)
                    <tr>
                        <td><a href="/administracija/magacin/pregled/{{$m['id']}}" class="btn btn-lg btn-default" data-toggle="tooltip" title="Pregledaj proizvode u magacinu"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                        <td><a href="/administracija/magacin/pregled/{{$m['id']}}" data-toggle="tooltip" title="Pregledaj proizvode u magacinu">{{$m['naziv']}}</a></td>
                        <td>{{$m['opis']}}</td>
                        <td>
                            <a href="/administracija/magacin/azuriraj/{{$m['id']}}" class="btn btn-lg btn-info" data-toggle="tooltip" title="Ažuriraj podatke o magacinu"><span class="glyphicon glyphicon-pencil"></span> Ažuriraj</a>
                            <a href="/administracija/magacin/ukloni/{{$m['id']}}" class="btn btn-lg btn-danger" data-toggle="tooltip" title="Ukloni magacin i sve proizvode iz njega"><span class="glyphicon glyphicon-trash"></span> Ukloni</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script>$(function(){$('[data-toggle="tooltip"]').tooltip()})</script>
    @else
        <p>Ni jedan magacin nije dodat u evidenciju.</p>
    @endif
    @endif

    @if(isset($novi) or isset($magacin))
        {!! Form::open(['url'=>'/administracija/magacin/magacin','class'=>'form-horizontal','id'=>'forma']) !!}
        @if(!isset($magacin))
            {!!$magacin = null!!}
        @else
            {!!Form::hidden('id',$magacin['id'])!!}
        @endif
        <div id="dnaziv" class="form-group has-feedback">
            {!! Form::label('lnaziv','Naziv',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::text('naziv',$magacin['naziv'],['placeholder'=>'Naziv','class'=>'form-control','id'=>'naziv']) !!}
                <span id="snaziv" class="glyphicon form-control-feedback"></span>
            </div>
        </div>

        <div id="dopis" class="form-group has-feedback">
            {!! Form::label('lopis','Opis',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::textarea('opis',$magacin['opis'],['placeholder'=>'Opis','class'=>'form-control','id'=>'opis']) !!}
                <span id="sopis" class="glyphicon form-control-feedback"></span>
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
@endsection