@extends('master')

@section('content')


    <div class="col-sm-8">
        <div class="alert alert-success" role="alert">
            <h2>Demo verzija</h2>
            <p>Prstupni podaci: <b>Admin</b> <button class="btn btn-xs btn-danger" onclick="prijavi('admin')"><i class="glyphicon glyphicon-log-in"></i></button></p>
            <p>Prstupni podaci: <b>Kula</b> <button class="btn btn-xs btn-warning" onclick="prijavi('eskula')"><i class="glyphicon glyphicon-log-in"></i></button></p>
            <p>Prstupni podaci: <b>Kulin radnik</b> <button class="btn btn-xs btn-primary" onclick="prijavi('radnik_0000')"><i class="glyphicon glyphicon-log-in"></i></button></p>
            <p>Prstupni podaci: <b>Tester Vlasnik</b> <button class="btn btn-xs btn-warning" onclick="prijavi('vltester')"><i class="glyphicon glyphicon-log-in"></i></button></p>
            <p>Prstupni podaci: <b>Tester Radnik</b> <button class="btn btn-xs btn-primary" onclick="prijavi('radtester_0000')"><i class="glyphicon glyphicon-log-in"></i></button></p>
            <p>Ovo je DEMO verzija koja se koristi isključivo za testiranje funkcionalnosti platforme i nije predviđena za poslovnu i drugu upotrebu. Trenutno je moguće testiranje platforme od strane vlasnika korisničkih aplikacija, možete se prijaviti kao vlasnici: Kula ili Tester. Panel za radnike će uskoro biti omogućen.</p>
        </div><script>function prijavi(str){$('#username').val(str);$('#password').val(str);$('.prijava').click()}</script>
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
                {!! Form::button('<span class="glyphicon glyphicon-play-circle"></span> Prijava', ['class' => 'btn btn-lg btn-primary prijava','onClick'=>'SubmitForm.submit(\'forma\')']) !!}
                {!! Form::button('<span class="glyphicon glyphicon-refresh"></span> Resetuj šifru', ['class' => 'btn btn-lg btn-warning']) !!}
            </div>
        </div>
        {!! Form::close() !!}
        
    <script>
        $(document).keypress(function(e) {
            if(e.which == 13) {
                SubmitForm.submit('forma');
            }
        });
    </script>
    </div>
@stop