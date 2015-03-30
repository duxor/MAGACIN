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
                        <a href="" class="btn btn-lg btn-success">Dodaj na stanje</a>
                        <a href="" class="btn btn-lg btn-warning">Skini sa stanja</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Nema dodatih proizvoda u evidenciji magacina.</p>
    @endif

@endsection