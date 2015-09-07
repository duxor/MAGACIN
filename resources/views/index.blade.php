@extends('master')

@section('content')

    <div class="col-sm-5">
        <h1>IS Magacin</h1>
        <p>Informacioni sistem za vođenje stanja magacina i poslovanja zasnovanom na korištenju većih magacinskih kapaciteta.</p>
        <p>
            IS Magacin omogućava funkcionalnosti:
            <ul class="col-sm-offset-2 ulul">
                <li>Višemagacinski pregled (u slučaju posjedovanja više objekata za potrebe skladištenja)</li>
                <li>Vođenje stanja magacina</li>
                <li>Obavještenja o isteku resursa</li>
                <li>Izrada narudžbenica i faktura</li>
                <li>Praćenje realizacije faktura</li>
                <li>Pretrage po različitim kriterijumima</li>
                <li>Izvoz podataka u pdf</li>
                <li>Štampanje ponude proizvoda</li>
                <li>Šematski prikaz magacina - skladišta</li>
                <li>Navigacija putem android aplikacije</li>
            </ul><style>.ulul li{font-size:20px}</style>
        </p>
    </div>

    <div class="col-sm-7">
        <div class="thumbnail">
            <img src="img/warehouse-1.jpg" style="width:100%">
            <div style="padding:10px">
                <h2>Magacin za nekoga može da predstavlja pravi lavirint iz koga se izlazi pozivom službe za spasavanje.</h2>
            </div>
        </div>
    </div>
@endsection