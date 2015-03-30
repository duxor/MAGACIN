@extends('masterBackEnd')
@section('content')

    @if(isset($zaNarudzbu))
        @if($zaNarudzbu)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
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
                        <td>{!!Form::checkbox('naruci',$stavka['proizvod_id'],null,['class'=>'checknaruci'])!!}</td>
                        <td>{{$stavka['sifra']}}</td>
                        <td>{{$stavka['naziv_proizvoda']}}</td>
                        <td>{{$stavka['kolicina_stanje']}}</td>
                        <td>{{$stavka['kolicina_min']}}</td>
                        <td><a href="/administracija/magacin/pregled/{{$stavka['magacinid_id']}}">{{$stavka['naziv_magacina']}}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="btn btn-lg btn-default" onclick="selektujSve()"><span class="glyphicon glyphicon-check"></span> Selektuj sve</button>
            <script>
                function selektujSve(){
                    var val = true;
                    if($('.checknaruci')[0].checked) val = false;
                    $('.checknaruci').each(function(){
                        this.checked = val;
                    });
                }
            </script>
            <button class="btn btn-lg btn-primary" onclick="naruci()"><span class="glyphicon glyphicon-list-alt"></span> Naruči</button>
            <script>
                function naruci(){
                    var i = 0, podaci = [];
                    $('.checknaruci').each(function(){
                        if(this.checked) podaci[i] = this.value;
                        i++;
                    });
                    if(podaci.length<1) alert('Selektujte proizvode za narudžbu.');
                    else{
                        $('#proizvodi').val(JSON.stringify(podaci));
                        $('#formanarudzba').submit();
                    }
                }
            </script>
            {!!Form::open(['url'=>'/administracija/proizvod/narudzbenica','id'=>'formanarudzba'])!!}
                {!!Form::hidden('proizvodi',null,['id'=>'proizvodi'])!!}
            {!!Form::close()!!}
        @else
            <p>Nema proizvoda za narudžbu.</p>
        @endif
    @endif

    @if(isset($proizvodi))
        @if($proizvodi)
            <!--
            --prikazuje se sifra, naziv, opis
            -trenutno imam
            -minimalno moram imati
            -unosim broj artikala
            >>klik na naruci

            >prikaz prednarudzbenice > pritisak na potvrdi
            >>>>
            >upis u tabelu
            >izvoz u pdf

            >>u arhivi se prikazuju necekirane narudzbenice
            >svaka stavka narudzbenice moze da se cekira - nakon cega se cekirani broj dodaje u bazu proizvoda
            -->
        @else
            <p>Nema proizvoda za narudžbu.</p>
        @endif
    @endif
@endsection