@extends('administracija.master')
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
                            {!!Form::open(['url'=>'/administracija/proizvod/narudzbe-potvrdi/'.$narudzba])!!}
                            <div class="form-group">
                                {!!Form::checkbox('naruceno')!!}
                                {!!Form::label('lnaruceno','Ne prikazuj u notifikacijama naručene proizvode')!!}
                            </div>
                            {!!Form::button('<span class="glyphicon glyphicon-check"></span> Naruči',['class'=>'btn btn-lg btn-primary','type'=>'submit'])!!}
                            <a href="/pdf/narudzba_{{$narudzba}}.pdf" target="_blank" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-list-alt"></span> PDF</a>
                            <a href="/administracija/proizvod/narudzbe-resetuj/{{$narudzba}}" class="btn btn-lg btn-danger"><span class="glyphicon glyphicon-trash"></span> Otkaži</a>
                            {!!Form::close()!!}
                        </td>
                        <td></td><td></td><td></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    @endif


    @if(isset($narudzbeArhiva))
        @if($narudzbeArhiva['neporuceno'] or $narudzbeArhiva['isporuceno'])
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Broj narudžbe</th>
                        <th>Datum narudžbe</th>
                        <th>Datum isporuke</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($narudzbeArhiva as $tabela)
                    @foreach($tabela as $narudzba)
                        <tr>
                            <td>
                                @if(!$narudzba['datum_isporuke'])
                                    <a href="/administracija/proizvod/narudzba-uredi/{{$narudzba['id']}}" class="btn btn-lg btn-default"><span class="glyphicon glyphicon-eye-open"></span></a>
                                @endif
                                <a href="/{{$narudzba['pdf']}}" target="_blank" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-eye-open"></span></a>
                            </td>
                            <td>{{$narudzba['id']}}</td>
                            <td>{{$narudzba['datum_narudzbe']}}</td>
                            <td>
                                @if($narudzba['datum_isporuke'])
                                    {{$narudzba['datum_isporuke']}}
                                @else
                                    Neisporučeno
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        @else
            <p>Ne postoji ni jedna narudžba u evidenciji.</p>
        @endif
    @endif

    @if(isset($pristiglo))
        <h2 style="text-align: left" class="col-sm-8">Datum narudzbe: {{$pristiglo[0]['datum_narudzbe']}}</h2>
        <h2 class="col-sm-4">
            {!!HTML::style('/css/datepicker.css')!!}
            {!!HTML::script('/js/datepicker.js')!!}
            @if($pristiglo[0]['datum_isporuke'])
                {{$pristiglo[0]['datum_isporuke']}}
            @else
                {!!Form::open(['url'=>'/administracija/proizvod/narudzba-datum-isporuke/'.$pristiglo[0]['narudzbeniceid'],'class'=>'form-inline'])!!}
                {!!Form::text('datum_isporuke',null,['class'=>'form-control','id'=>'datum'])!!}
                {!!Form::button('<i class="glyphicon glyphicon-ok"></i>',['class'=>'btn btn-warning','type'=>'submit'])!!}
                {!!Form::close()!!}
                <script>
                    $('#datum').datepicker({
                        format: "yyyy-mm-dd",
                        weekStart: 1,
                        todayBtn: "linked",
                        autoclose: true,
                        todayHighlight: true,
                        orientation: 'top auto'
                    });
                    $(function() {
                        $('#datum').datepicker().datepicker("setDate", "0");
                    });
                </script>
            @endif
        </h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Magacin</th>
                    <th>Šifra</th>
                    <th>Naziv</th>
                    <th>Poručeno</th>
                    <th>Pristiglo</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pristiglo as $proizvod)
                    <tr>
                        <td><a href="/administracija/magacin/pregled/{{$proizvod['magacin_id']}}">{{$proizvod['magacin']}}</a></td>
                        <td>{{$proizvod['sifra']}}</td>
                        <td>{{$proizvod['naziv']}}</td>
                        <td>{{$proizvod['kolicina_porucena']}}</td>
                        <th>{{$proizvod['kolicina_pristigla']}}</th>
                        <td>
                            {!!Form::open(['url'=>'/administracija/proizvod/narudzba-uredi/'.$proizvod['id'],'class'=>'form-inline'])!!}
                                {!!Form::hidden('magacin_id',$proizvod['magacin_id'])!!}
                                {!!Form::text('kolicina_pristigla',0,['class'=>'form-control'])!!}
                                {!!Form::button('<i class="glyphicon glyphicon-plus"></i>',['class'=>'btn btn-primary','type'=>'submit'])!!}
                            {!!Form::close()!!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection