@extends('administracija.master')

@section('content')


    <div class="col-sm-7">
        <div class="alert alert-success" role="alert">
            <h2>Demo verzija</h2>
            <p>Prstupni podaci: <b>admin / admin</b></p>
        </div>
        <h1>Prijava</h1>
        <hr/>
        {!! Form::open(['url'=>'administracija/login','class'=>'form-horizontal','id'=>'forma']) !!}
        <div id="dusername" class="form-group has-feedback">
            {!! Form::label('lusername','Username',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::text('username',Input::old('username'),['placeholder'=>'Korisničko ime','class'=>'form-control','id'=>'username']) !!}
                <span id="susername" class="glyphicon form-control-feedback"></span>
            </div>
        </div>

        <div id="dpassword" class="form-group has-feedback">
            {!! Form::label('lpassword','Password',['class'=>'control-label col-sm-2']) !!}
            <div class="col-sm-10">
                {!! Form::password('password', ['placeholder'=>'Pristupna šifra','class'=>'form-control','id'=>'password']) !!}
                <span id="spassword" class="glyphicon form-control-feedback"></span>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                {!! Form::button('<span class="glyphicon glyphicon-play-circle"></span> Prijava', ['class' => 'btn btn-lg btn-primary','onClick'=>'SubmitForm.submit(\'forma\')']) !!}
                {!! Form::button('<span class="glyphicon glyphicon-refresh"></span> Resetuj šifru', ['class' => 'btn btn-lg btn-warning']) !!}
            </div>
        </div>
        {!! Form::close() !!}
        
    <script>
        $(document).keypress(function(e) {
            if(e.which == 13) {
                SubmitForma.submit('forma');
            }
        });
    </script>
    </div>
@stop