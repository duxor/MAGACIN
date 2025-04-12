@extends('app-admin.master.prazan')
@section('content')
    <h1 style="text-align: left">Verzioniranje</h1>
    <h2 style="text-align: left">Aktuelna verzija platforme: v1.5.2</h2>
    <hr>
        <div class="col-sm-3">v1.5.2 (13.12.2015.)</div>
        <div class="col-sm-9">
            <ul>
                <li>Ispravljena greška ažuriranja korisničkog prava pristupa.</li>
                <li>Preciziran je filter pri ispisu podataka za korisnika u kreiranju fakture.</li>
                <li>*Implementirana je mogućnost dodavanja nove pozicije za proizvode koji se ne nalaze u magacinu pri magaciniranju pristigle robe.</li>
                <li>Uklonjen je nepotrebni ispis prilikom kreiranja fakture početno stanje.</li>
                <li>Otklonjena je greška prikaza i pretrage proizvoda u odjeljku upustvo.</li>
                <li>Dodat je video sa osnovnim uputstvom za korištenje platforme.</li>
            </ul>
        </div><br clear="all">
    <hr>
        <div class="col-sm-3">v1.5.1 (12.12.2015.)</div>
        <div class="col-sm-9">
            <ul>
                <li>Dodato je kratko uputstvo za korištenje platforme.</li>
            </ul>
        </div><br clear="all">
    <hr>
        <div class="col-sm-3">v1.5 (10.12.2015.)</div>
        <div class="col-sm-9">
            <ul>
                <li>Dodate su stavke default log-a.</li>
                <li>Uklanjanje fakture je rekonstruisano radi i lokalizovano na app nivou očuvanja bezbijednosti.</li>
                <li>Izvršena je dorada lokalizacije e - ije za osnovni templejt i fakurisanje.</li>
                <li>U dijelu sa proizvodima uklonjeni dead linkovi.</li>
            </ul>
        </div><br clear="all">
    <hr>
        <div class="col-sm-3">v1.4 (09.12.2015.)</div>
        <div class="col-sm-9">
            <ul>
                <li>Omogućen je pregled proizvoda određenog magacina.</li>
                <li>Otklonjena je greška višeaplikativnog prikaza.</li>
                <li>Dodata je još jedna aplikacija za testiranje.</li>
                <li>U ispis fakture dodat je dinamički unos futera po aplikaciji.</li>
                <li>Log funkcionalnost proširen ip nivoom.</li>
                <li>Integrisana funkcionalnost multijezičnosti.</li>
            </ul>
        </div><br clear="all">
    <hr>
        <div class="col-sm-3">v1.3 (14.11.2015.)</div>
        <div class="col-sm-9">
            <ul>
                <li>Omogućena je funkcionalnost uklanjanja korisnika.</li>
                <li>Dodata je funkcionalnost pregleda faktura po korisnicima.</li>
            </ul>
        </div><br clear="all">
    <hr>
    <div class="col-sm-3">v1.2 (13.11.2015.)</div>
    <div class="col-sm-9">
        <ul>
            <li>Pregled faktura je proširen i stilizovan.</li>
            <li>Dodat je pregled faktura po godinama.</li>
            <li>Dodata su proširenja u dijelu korisnika (bez realizacije funkcionalnosti brisanja korisnika i pregleda faktura vezanih za korisnika).</li>
        </ul>
    </div><br clear="all">
    <hr>
    <div class="col-sm-3">v1.1 (04.11.2015.)</div>
    <div class="col-sm-9">
        <ul>
            <li>Dodato je slug polje u tabelu vrsta fakture.</li>
            <li>Omogućeno je odvajanje inicijalnih faktura u poseban folder sa komletnom funkcionalnošću.</li>
            <li>Omogućen je osnovni pregled faktura, ukupno i po vrstama.</li>
            <li>Omogućen je brisanje faktura.</li>
            <li>Ispravljene su greške u funkcionisanju određenih dijelova koda.</li>
        </ul>
    </div><br clear="all">
    <hr>
    <div class="col-sm-3">v1.0.1 (27.10.2015.)</div>
    <div class="col-sm-9">
        <ul>
            <li>Dodata je funkcionalnost pregleda verzioniranja platforme.</li>
            <li>Dodato je inicijalno fakturisanje, za postavlja nje početnog stanja magacina.</li>
            <li>Korisnička pretraga je omogućena na promjenu vrste korisnika.</li>
            <li>Pretraga proizvoda je omogućena na promjenu vrste proizvoda.</li>
        </ul>
    </div>
@endsection