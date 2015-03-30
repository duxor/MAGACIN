@extends('masterBackEnd')
@section('content')

    <h1 style="text-align: left">
        {{$magacin['naziv']}}
        <a href="/administracija/magacin/azuriraj/{{$magacin['id']}}" class="btn btn-lg btn-info">
            <span class="glyphicon glyphicon-pencil"></span> Ažuriraj
        </a>
        <a href="/administracija/proizvod" class="btn btn-lg btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Dodaj proizvod
        </a>
    </h1>
    @if($umagacinu)
        <table class="table table-striped" style="margin-top: 50px">
            <thead>
                <tr>
                    <th>Šifra</th>
                    <th>Naziv</th>
                    <th>Na stanju</th>
                    <th>Minimum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($umagacinu as $proizvod)
                <tr>
                    <td>{{$proizvod['sifra']}}</td>
                    <td>{{$proizvod['naziv']}}</td>
                    <td>{{$proizvod['kolicina_stanje']}}</td>
                    <td>{{$proizvod['kolicina_min']}}</td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#modal" class="btn btn-lg btn-success" onclick="magacinid({{$proizvod['id']}},'+')"><span class="glyphicon glyphicon-plus"></span> Dodaj na stanje</a>
                        <a href="#" data-toggle="modal" data-target="#modal" class="btn btn-lg btn-danger" onclick="magacinid({{$proizvod['id']}},'-')"><span class="glyphicon glyphicon-minus"></span> Skini sa stanja</a>
                        <a href="#" data-toggle="modal" data-target="#modal" class="btn btn-lg btn-primary" onclick="magacinid({{$proizvod['id']}},'min')"><span class="glyphicon glyphicon-indent-left"></span> Ažuriraj minimum</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <script>
            function magacinid(id,znak){
                $('#magacin_id').val(id);
                $('#znak').val(znak);
                $('#lkolicina_stanje').text(znak=='+'?'Broj proizvoda za dodavanje na stanje':znak=='-'?'Broj proizvoda za skidanje sa stanja':'Ažuriraj minimum');
            }
        </script>
    @else
        <p>Nema dodatih proizvoda u evidenciji magacina.</p>
    @endif

@endsection

@section('body')
    @if($umagacinu)
    <div id="modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                {!!Form::open(['url'=>'/administracija/magacin/proizvod','id'=>'forma','class'=>'form-horizontal'])!!}
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h2>Dodaj na stanje</h2>
                </div>
                <div class="modal-body">
                    {!!Form::hidden('magacin_id',null,['id'=>'magacin_id'])!!}
                    {!!Form::hidden('znak',null,['id'=>'znak'])!!}
                    <div class="form-group">
                        {!!Form::label('lkolicina_stanje','',['for'=>'kolicina_stanje','class'=>'control-label col-sm-9','id'=>'lkolicina_stanje'])!!}
                        <div class="col-sm-3">
                            {!!Form::text('kolicina_stanje', 1, ['class'=>'form-control'])!!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!!Form::button('<span class="glyphicon glyphicon-play-circle"></span> Sačuvaj',['class'=>'btn btn-lg btn-primary','type'=>'submit'])!!}
                    {!!Form::button('<span class="glyphicon glyphicon-refresh"></span> Obriši unos',['class'=>'btn btn-lg btn-danger','type'=>'reset'])!!}
                </div>
                {!!Form::close()!!}
            </div>
        </div>
    </div>
    @endif
@endsection