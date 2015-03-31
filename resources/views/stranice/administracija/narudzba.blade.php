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
                        <td>{!!Form::checkbox('naruci',$stavka['id'],null,['class'=>'checknaruci'])!!}</td>
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
            {!!Form::open(['url'=>'/administracija/proizvod/prednarudzba'])!!}
            <div id="datum" class="col-sm-2">
                <div class="input-group date">
                    {!!Form::text('datum',null,['class'=>'form-control'])!!}
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
            </div>
                {!!HTML::style('/css/datepicker.css')!!}
                {!!HTML::script('/js/datepicker.js')!!}
            <script>
                $('#datum .input-group.date').datepicker({
                    format: "yyyy-mm-dd",
                    weekStart: 1,
                    todayBtn: "linked",
                    autoclose: true,
                    todayHighlight: true
                });
                $(function() {
                    $('#datum .input-group.date').datepicker().datepicker("setDate", "0");
                });
            </script>
            <table class="table table-striped" style="margin-top: 50px">
                <thead>
                    <tr>
                        <th>Šifra</th>
                        <th>Naziv</th>
                        <th>Opis</th>
                        <th>Na stanju</th>
                        <th>Minimum</th>
                        <th>Za narudžbu</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($proizvodi as $proizvod)
                    <tr>
                        <td>{{$proizvod['sifra']}}</td>
                        <td>{{$proizvod['naziv']}}</td>
                        <td>{{$proizvod['opis']}}</td>
                        <td>{{$proizvod['kolicina_stanje']}}</td>
                        <td>{{$proizvod['kolicina_min']}}</td>
                        <td>
                            {!!Form::text('kolicina_narudzba['.$proizvod['id'].']',1,['class'=>'form-control'])!!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td>
                        {!!Form::button('<span class="glyphicon glyphicon-check"></span> Naruči',['class'=>'btn btn-lg btn-primary','type'=>'submit'])!!}
                    </td>
                    <td></td><td></td><td></td><td></td><td></td>
                </tr>
                </tfoot>
            </table>
            {!!Form::close()!!}

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

    @if(isset($prednarudzbenica))
        @if($prednarudzbenica)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>R.Br</th>
                        <th>Šifra</th>
                        <th>Naziv</th>
                        <th>Količina</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prednarudzbenica as $k => $proizvod)
                        <tr>
                            <td>{{$k}}</td>
                            <td>{{$proizvod['sifra']}}</td>
                            <td>{{$proizvod['naziv']}}</td>
                            <td>{{$proizvod['kolicina_naruceno']}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <a href="/administracija/proizvod/narudzbe-potvrdi/{{$narudzba}}" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-check"></span> Naruči</a>
                            <a href="/administracija/proizvod/narudzbe-resetuj/{{$narudzba}}" class="btn btn-lg btn-danger"><span class="glyphicon glyphicon-trash"></span> Otkaži</a>
                        </td>
                        <td></td><td></td><td></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    @endif
@endsection