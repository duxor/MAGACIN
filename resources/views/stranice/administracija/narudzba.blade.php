@extends('masterBackEnd')
@section('content')

    @if(isset($zaNarudzbu))
        @if($zaNarudzbu)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Šifra</th>
                        <th>Naziv</th>
                        <th>Na stanju</th>
                        <th>Minimum</th>
                        <th>Magacin</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($zaNarudzbu as $stavka)
                    <tr>
                        <td>{{$stavka['sifra']}}</td>
                        <td>{{$stavka['naziv_proizvoda']}}</td>
                        <td>{{$stavka['kolicina_stanje']}}</td>
                        <td>{{$stavka['kolicina_min']}}</td>
                        <td><a href="/administracija/magacin/pregled/{{$stavka['magacinid_id']}}">{{$stavka['naziv_magacina']}}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>Nema proizvoda za narudžbu.</p>
        @endif
    @endif

@endsection