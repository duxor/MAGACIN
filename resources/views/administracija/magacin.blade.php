@extends('administracija.master')
@section('content')

    <h1 style="text-align: left">
        {{$magacin['naziv']}}
        <a href="/administracija/magacin/azuriraj/{{$magacin['id']}}" class="btn btn-lg btn-info" data-toggle="tooltip" title="Ažuriraj podatke o magacinu"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="/administracija/proizvod" class="btn btn-lg btn-primary" data-toggle="tooltip" data-placement="bottom" title="Dodaj proizvod u magacin"><span class="glyphicon glyphicon-plus"></span></a>
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
                        <a href="#" data-toggle="modal" data-target="#modal" class="btn btn-lg btn-success _tooltip" onclick="magacinid({{$proizvod['id']}},'+')" title="Dodaj na stanje"><span class="glyphicon glyphicon-plus"></span></a>
                        <a href="#" data-toggle="modal" data-target="#modal" class="btn btn-lg btn-danger _tooltip" onclick="magacinid({{$proizvod['id']}},'-')" title="Skini sa stanja"><span class="glyphicon glyphicon-minus"></span></a>
                        <a href="#" data-toggle="modal" data-target="#modal" class="btn btn-lg btn-primary _tooltip" onclick="magacinid({{$proizvod['id']}},'min')" title="Ažuriraj minimum"><span class="glyphicon glyphicon-indent-left"></span></a>
                        <a href="/administracija/magacin/proizvod-ukloni/{{$proizvod['id']}}" class="btn btn-lg btn-danger" data-toggle="tooltip" title="Ukloni proizvod iz magacina"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <script>
            $(function(){$('._tooltip').tooltip();$('[data-toggle="tooltip"]').tooltip()})
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