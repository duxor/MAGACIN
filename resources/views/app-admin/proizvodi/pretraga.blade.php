@extends('admin-master')
@section('content')
    {!!Form::open(['url'=>'/administracija/proizvod/pretraga','class'=>'form-inline'])!!}
    {!!Form::input('search','sifra', null, ['placeholder'=>'Unesite šifru ili naziv','class'=>'form-control'])!!}
    {!!Form::button('<i class="glyphicon glyphicon-search"></i> Pretraga',['class'=>'btn btn-lg btn-primary','type'=>'submit'])!!}
    {!!Form::close()!!}
    @if(isset($rezultati))
        @if($rezultati)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Naziv magacina</th>
                        <th>Šifra</th>
                        <th>Proizvod</th>
                        <th>Stanje</th>
                        <th>Stolaza</th>
                        <th>Polica</th>
                        <th>Pozicija</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rezultati as $rezultat)
                    <tr>
                        <td><a href="/administracija/magacin/pregled/{{$rezultat['id']}}">{{$rezultat['nazivmagacina']}}</a></td>
                        <td>{{$rezultat['nazivproizvoda']}}</td>
                        <td>{{$rezultat['sifra']}}</td>
                        <td>{{$rezultat['kolicina_stanje']}}</td>
                        <td>{{$rezultat['stolaza']}}</td>
                        <td>{{$rezultat['polica']}}</td>
                        <td>{{$rezultat['pozicija']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>Nema rezultata za prikaz.</p>
        @endif
    @endif

@endsection