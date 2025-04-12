<?php

use Illuminate\Database\Seeder;
use App\Proizvodi;
use App\MagaciniID;
use App\Pozicija;
use App\Magacin;
use App\Korisnici;
use App\Security;
use App\Aplikacija;
use App\VrstaProizvoda;
use App\KorisniciAplikacije;
use App\PozicijaUMagacinu;

class TestPodaci extends Seeder{

    public function run(){
        Aplikacija::insert([
            [//1
                'naziv' => 'SZTR "KULA" FOČA',
                'slug'=>'eskulaf',
                'korisnici_id'=>2,
                'opis'=>'',
                'napomena'=>'',
                'logo'=>'',
                'adresa' => 'Ul. Svetosavska bb',
                'grad' => '73300 Foča',
                'jib' => '4503782250007',
                'pib' => '',
                'pdv' => '503782250007',
                'ziro_racun_1' => '551-404-11288935-39',
                'banka_1' => 'Uni Credit Bank',
                'ziro_racun_2' => '562-006-00002081-69',
                'banka_2' => 'Razvojna banka Foča',
                'registracija' => 'Opština Foča',
                'broj_upisa' => '05-350-47',
                'telefon' => '',
                'faktura_futer_1' => 'Reklamacije se uvažavaju u roku od 8 dana po prijemu robe i usluge',
                'faktura_futer_2' => 'Za sve sporove nadležan je Osnovni sud u Foči',
                'faktura_futer_3' => 'Hvala na povjerenju!',
                'jezik'=>'sr-i'
            ],
            [//2
                'naziv' => 'SZTR "TESTIRANJE PLATFORME"',
                'slug'=>'testiranje',
                'korisnici_id'=>4,
                'opis'=>'',
                'napomena'=>'',
                'logo'=>'',
                'adresa' => 'Ulica i broj',
                'grad' => 'Grad PBr',
                'jib' => 'JIB broj',
                'pib' => 'PIB broj',
                'pdv' => 'PDV broj',
                'ziro_racun_1' => 'Tekući-Žiro račun 1',
                'banka_1' => 'Naziv banke 1',
                'ziro_racun_2' => 'Tekući-Žiro račun 2',
                'banka_2' => 'Naziv banke 2',
                'registracija' => 'Lokacija registracije',
                'broj_upisa' => 'BROJ UPISA',
                'telefon' => 'Broj telefona',
                'faktura_futer_1' => 'Reklamacije se uvažavaju u roku od 8 dana po prijemu robe i usluge.',
                'faktura_futer_2' => 'Za sve sporove nadležan je Osnovni sud u GRAD',
                'faktura_futer_3' => 'Hvala na poverenju!',
                'jezik'=>'sr-e'
            ]
        ]);
        VrstaProizvoda::insert([
            [
                'naziv'=>'Veliki kućanski aparati',
                'napomena'=>'',
                'aplikacija_id'=>1
            ],
            [
                'naziv'=>'Mali kućanski aparati',
                'napomena'=>'',
                'aplikacija_id'=>1
            ],
            [
                'naziv'=>'Veliki kućanski aparati',
                'napomena'=>'',
                'aplikacija_id'=>2
            ],
            [
                'naziv'=>'Mali kućanski aparati',
                'napomena'=>'',
                'aplikacija_id'=>2
            ]
        ]);
        Proizvodi::insert([
            [//1
                'sifra'=>'test001',
                'naziv'=>'Mašina za veš',
                'opis'=>'Velika, sa dimenzijama LxMxD',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>1,
                'aplikacija_id'=>1,
                'foto'=>'/img/aplikacije/eskulaf/proizvodi/elektroservis-kula-foca-WA610SYB.jpg'
            ],
            [//2
                'sifra'=>'test002',
                'naziv'=>'Mašina za posuđe',
                'opis'=>'Uspravna, sa velikim poklopcem.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>1,
                'aplikacija_id'=>1,
                'foto'=>'/img/aplikacije/eskulaf/proizvodi/elektroservis-kula-foca-GV61124.jpg'
            ],
            [//3
                'sifra'=>'test003',
                'naziv'=>'Usisivač',
                'opis'=>'Automatski sa robotskim funkcijama.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>2,
                'aplikacija_id'=>1,
                'foto'=>'/img/aplikacije/eskulaf/proizvodi/usisivac-3.jpg'
            ],
            [//4
                'sifra'=>'test004',
                'naziv'=>'Bojler',
                'opis'=>'Veliki od 200l',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>1,
                'aplikacija_id'=>1,
                'foto'=>'/img/aplikacije/eskulaf/proizvodi/bojler-4.jpg'
            ],
            [//5
                'sifra'=>'test005',
                'naziv'=>'Kuhinjska napa',
                'opis'=>'Utisnuta, sa velikim kapacitetom i garancijom na 5 godina.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>1,
                'aplikacija_id'=>1,
                'foto'=>'/img/aplikacije/eskulaf/proizvodi/elektroservis-kula-foca-IDKG9545E.jpg'
            ],
            [//6
                'sifra'=>'test007',
                'naziv'=>'Mikser',
                'opis'=>'Ručni, sa 5 funkcionalnih brzina.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>2,
                'aplikacija_id'=>1,
                'foto'=>'/img/aplikacije/eskulaf/proizvodi/mikser.jpg'
            ],
            //##############################################################################
            [//1
                'sifra'=>'test001',
                'naziv'=>'Mašina za veš',
                'opis'=>'Velika, sa dimenzijama LxMxD',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>3,
                'aplikacija_id'=>2,
                'foto'=>'/img/aplikacije/testiranje/proizvodi/elektroservis-kula-foca-WA610SYB.jpg'
            ],
            [//2
                'sifra'=>'test002',
                'naziv'=>'Mašina za posuđe',
                'opis'=>'Uspravna, sa velikim poklopcem.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>3,
                'aplikacija_id'=>2,
                'foto'=>'/img/aplikacije/testiranje/proizvodi/elektroservis-kula-foca-GV61124.jpg'
            ],
            [//3
                'sifra'=>'test003',
                'naziv'=>'Usisivač',
                'opis'=>'Automatski sa robotskim funkcijama.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>4,
                'aplikacija_id'=>2,
                'foto'=>'/img/aplikacije/testiranje/proizvodi/usisivac-3.jpg'
            ],
            [//4
                'sifra'=>'test004',
                'naziv'=>'Bojler',
                'opis'=>'Veliki od 200l',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>3,
                'aplikacija_id'=>2,
                'foto'=>'/img/aplikacije/testiranje/proizvodi/bojler-4.jpg'
            ],
            [//5
                'sifra'=>'test005',
                'naziv'=>'Kuhinjska napa',
                'opis'=>'Utisnuta, sa velikim kapacitetom i garancijom na 5 godina.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>3,
                'aplikacija_id'=>2,
                'foto'=>'/img/aplikacije/testiranje/proizvodi/elektroservis-kula-foca-IDKG9545E.jpg'
            ],
            [//6
                'sifra'=>'test007',
                'naziv'=>'Mikser',
                'opis'=>'Ručni, sa 5 funkcionalnih brzina.',
                'bar_kod'=>'',
                'proizvodjac'=>'Gorenje',
                'jedinica_mjere'=>'Kom',
                'pakovanje_kolicina'=>1,
                'pakovanje_jedinica_mjere'=>'Paket',
                'vrsta_proizvoda_id'=>4,
                'aplikacija_id'=>2,
                'foto'=>'/img/aplikacije/testiranje/proizvodi/mikser.jpg'
            ],
        ]);
        MagaciniID::insert([
            [//1
                'naziv'=>'Magacin 1',
                'opis'=>'Magacin u ulici Miloša Obilića 69',
                'aplikacija_id'=>1
            ],
            [//2
                'naziv'=>'Magacin 2',
                'opis'=>'Magacin u naselju Vojvode Putnika',
                'aplikacija_id'=>1
            ],
            [//3
                'naziv'=>'Magacin 3',
                'opis'=>'Magacin u skladištu 5',
                'aplikacija_id'=>1
            ],

            [//4
                'naziv'=>'Magacin 1',
                'opis'=>'Magacin u ulici Miloša Obilića 69',
                'aplikacija_id'=>2
            ],
            [//5
                'naziv'=>'Magacin 2',
                'opis'=>'Magacin u naselju Vojvode Putnika',
                'aplikacija_id'=>2
            ],
            [//6
                'naziv'=>'Magacin 3',
                'opis'=>'Magacin u skladištu 5',
                'aplikacija_id'=>2
            ],
        ]);
        Pozicija::insert([
            [//1
                'stolaza'=>1,
                'polica'=>1,
                'pozicija'=>1,
                'opis'=>'Srednja, na početku...',
                'aplikacija_id'=>1
            ],
            [//2
                'stolaza'=>1,
                'polica'=>2,
                'pozicija'=>1,
                'opis'=>'Opis neki 1.....',
                'aplikacija_id'=>1
            ],
            [//3
                'stolaza'=>2,
                'polica'=>1,
                'pozicija'=>3,
                'opis'=>'Opis neki 2.....',
                'aplikacija_id'=>1
            ],
            [//4
                'stolaza'=>2,
                'polica'=>1,
                'pozicija'=>5,
                'opis'=>'Opis neki 3.....',
                'aplikacija_id'=>1
            ],
            ///################################################
            [//1
                'stolaza'=>1,
                'polica'=>1,
                'pozicija'=>1,
                'opis'=>'Srednja, na početku...',
                'aplikacija_id'=>2
            ],
            [//2
                'stolaza'=>1,
                'polica'=>2,
                'pozicija'=>1,
                'opis'=>'Opis neki 1.....',
                'aplikacija_id'=>2
            ],
            [//3
                'stolaza'=>2,
                'polica'=>1,
                'pozicija'=>3,
                'opis'=>'Opis neki 2.....',
                'aplikacija_id'=>2
            ],
            [//4
                'stolaza'=>2,
                'polica'=>1,
                'pozicija'=>5,
                'opis'=>'Opis neki 3.....',
                'aplikacija_id'=>2
            ],
        ]);
        Magacin::insert([
            [//1
                'magacin_id_id'=>1,
                'proizvod_id'=>1,
                'kolicina_min'=>3,
                'cijena'=>400
            ],
            [//2
                'magacin_id_id'=>1,
                'proizvod_id'=>2,
                'kolicina_min'=>5,
                'cijena'=>600
            ],
            [//3
                'magacin_id_id'=>1,
                'proizvod_id'=>3,
                'kolicina_min'=>21,
                'cijena'=>300
            ],
            [//4
                'magacin_id_id'=>2,
                'proizvod_id'=>5,
                'kolicina_min'=>23,
                'cijena'=>200
            ],
            [//5
                'magacin_id_id'=>2,
                'proizvod_id'=>1,
                'kolicina_min'=>12,
                'cijena'=>500
            ],
            [//6
                'magacin_id_id'=>2,
                'proizvod_id'=>3,
                'kolicina_min'=>12,
                'cijena'=>400
            ],
            [//7
                'magacin_id_id'=>3,
                'proizvod_id'=>1,
                'kolicina_min'=>25,
                'cijena'=>340
            ],
            [//8
                'magacin_id_id'=>3,
                'proizvod_id'=>2,
                'kolicina_min'=>7,
                'cijena'=>500
            ],
            [//9
                'magacin_id_id'=>3,
                'proizvod_id'=>3,
                'kolicina_min'=>2,
                'cijena'=>450
            ],
            [//10
                'magacin_id_id'=>3,
                'proizvod_id'=>4,
                'kolicina_min'=>3,
                'cijena'=>200
            ],
            ///////////////////////////////////
            [//11
                'magacin_id_id'=>4,
                'proizvod_id'=>7,
                'kolicina_min'=>3,
                'cijena'=>19500
            ],
            [//2
                'magacin_id_id'=>4,
                'proizvod_id'=>8,
                'kolicina_min'=>5,
                'cijena'=>31000
            ],
            [//3
                'magacin_id_id'=>4,
                'proizvod_id'=>9,
                'kolicina_min'=>21,
                'cijena'=>1610
            ],
            [//4
                'magacin_id_id'=>5,
                'proizvod_id'=>11,
                'kolicina_min'=>23,
                'cijena'=>11200
            ],
            [//5
                'magacin_id_id'=>5,
                'proizvod_id'=>7,
                'kolicina_min'=>12,
                'cijena'=>27800
            ],
            [//6
                'magacin_id_id'=>5,
                'proizvod_id'=>9,
                'kolicina_min'=>12,
                'cijena'=>22700
            ],
            [//7
                'magacin_id_id'=>6,
                'proizvod_id'=>7,
                'kolicina_min'=>25,
                'cijena'=>18900
            ],
            [//8
                'magacin_id_id'=>6,
                'proizvod_id'=>8,
                'kolicina_min'=>7,
                'cijena'=>26800
            ],
            [//9
                'magacin_id_id'=>6,
                'proizvod_id'=>9,
                'kolicina_min'=>2,
                'cijena'=>24500
            ],
            [//10
                'magacin_id_id'=>6,
                'proizvod_id'=>10,
                'kolicina_min'=>3,
                'cijena'=>10800
            ],
        ]);
        Korisnici::insert([
            [//dobavljac ##6
                'prezime' => 'Petrović',
                'ime' => 'Petar',
                'email' => 'petrovic.petar@petrovo.com',
                'username' => 'petar2',
                'password' => Security::generateHashPass('petar2'),
                'prava_pristupa_id' => 3,//dobavljac
                'naziv' => 'Servis Petrovo',
                'adresa' => 'Ul. Miloša Obilića bb',
                'grad' => '73300 Foča',
                'jib' => '2222222',
                'pib' => '',
                'pdv' => '22222222222',
                'ziro_racun_1' => '434535345535',
                'banka_1' => 'Uni Credit Bank',
                'ziro_racun_2' => '32432433443232',
                'banka_2' => 'Razvojna banka Foča',
                'registracija' => 'Opština Foča',
                'broj_upisa' => '5443323',
                'telefon' => '058/211-999',
                'foto'=>'/img/aplikacije/testiranje/korisnici/6.jpg'
            ],
            [//kupac - fizicko lice ##7
                'prezime' => 'Marković',
                'ime' => 'Marko',
                'email' => 'markovicc@gmail.com',
                'username' => 'marko2',
                'password' => Security::generateHashPass('marko2'),
                'prava_pristupa_id' => 2,//kupac
                'naziv' => null,
                'adresa' => 'Ul. Cara Dusana bb',
                'grad' => '73300 Foča',
                'jib' => null,
                'pib' => null,
                'pdv' => null,
                'ziro_racun_1' => null,
                'banka_1' => null,
                'ziro_racun_2' => null,
                'banka_2' => null,
                'registracija' => null,
                'broj_upisa' => null,
                'telefon' => '065/290-331',
                'foto'=>'/img/aplikacije/testiranje/korisnici/7.jpg'
            ],
            [//8
                'prezime' => 'Dušan',
                'ime' => 'Perišić',
                'email' => 'kontakt@dusanperisic.com',
                'username' => 'duxor',
                'password' => Security::generateHashPass('duxor'),
                'prava_pristupa_id' => 3,//dobavljac
                'naziv' => 'PERSHING',
                'adresa' => 'Miloša Obilića 69',
                'grad' => '73300 Foča',
                'jib' => '434234242-jib',
                'pib' => '34324243234-pib',
                'pdv' => '3443243243-pdv',
                'ziro_racun_1' => '34-2342-324',
                'banka_1' => 'Komercijalna Banka a.d.',
                'ziro_racun_2' => null,
                'banka_2' => null,
                'registracija' => 'Foča Reg',
                'broj_upisa' => '34332/12',
                'telefon' => '065/290-346',
                'foto'=>'/img/aplikacije/testiranje/korisnici/8.jpg'
            ],
            ///////////////////////////
            [//dobavljac ##9
                'prezime' => 'Petrović',
                'ime' => 'Petar',
                'email' => 'petrovic.petar@petrovo.com',
                'username' => 'petar1',
                'password' => Security::generateHashPass('petar1'),
                'prava_pristupa_id' => 3,//dobavljac
                'naziv' => 'Servis Petrovo',
                'adresa' => 'Ul. Miloša Obilića bb',
                'grad' => 'Grad i poštanski broj',
                'jib' => '2222222',
                'pib' => '',
                'pdv' => '22222222222',
                'ziro_racun_1' => '434535345535',
                'banka_1' => 'Uni Credit Bank',
                'ziro_racun_2' => '32432433443232',
                'banka_2' => 'Razvojna banka',
                'registracija' => 'OšTINA REGISTRACIJE',
                'broj_upisa' => '5443323',
                'telefon' => '063/211-999',
                'foto'=>'/img/aplikacije/testiranje/korisnici/9.jpg'
            ],
            [//kupac - fizicko lice ##10
                'prezime' => 'Marković',
                'ime' => 'Marko',
                'email' => 'markovicc@gmail.com',
                'username' => 'marko1',
                'password' => Security::generateHashPass('marko1'),
                'prava_pristupa_id' => 2,//kupac
                'naziv' => null,
                'adresa' => 'Ulica i broj',
                'grad' => 'Grad i poštanski broj',
                'jib' => null,
                'pib' => null,
                'pdv' => null,
                'ziro_racun_1' => null,
                'banka_1' => null,
                'ziro_racun_2' => null,
                'banka_2' => null,
                'registracija' => null,
                'broj_upisa' => null,
                'telefon' => '065/290-331',
                'foto'=>'/img/aplikacije/testiranje/korisnici/10.jpg'
            ],
            [//11
                'prezime' => 'Milić',
                'ime' => 'Miloje',
                'email' => 'milic.miloje@domen.com',
                'username' => 'miloje',
                'password' => Security::generateHashPass('miloje'),
                'prava_pristupa_id' => 3,//dobavljac
                'naziv' => 'MILOJEVAC',
                'adresa' => 'Ulica i broj',
                'grad' => 'Grad i pošta',
                'jib' => '434234242-jib',
                'pib' => '34324243234-pib',
                'pdv' => '3443243243-pdv',
                'ziro_racun_1' => '34-2342-324',
                'banka_1' => 'Komercijalna Banka a.d.',
                'ziro_racun_2' => null,
                'banka_2' => null,
                'registracija' => 'Opština Reg',
                'broj_upisa' => '34332/12',
                'telefon' => '065/243-346',
                'foto'=>'/img/aplikacije/testiranje/korisnici/11.jpg'
            ]
        ]);
        PozicijaUMagacinu::insert([
            ['magacin_id'=>1,'pozicija_id'=>1,'kolicina'=>5],
            ['magacin_id'=>1,'pozicija_id'=>3,'kolicina'=>26],
            ['magacin_id'=>2,'pozicija_id'=>2,'kolicina'=>43],
            ['magacin_id'=>3,'pozicija_id'=>3,'kolicina'=>1],
            ['magacin_id'=>4,'pozicija_id'=>4,'kolicina'=>17],
            ['magacin_id'=>4,'pozicija_id'=>1,'kolicina'=>9],
            ['magacin_id'=>5,'pozicija_id'=>2,'kolicina'=>11],
            ['magacin_id'=>6,'pozicija_id'=>3,'kolicina'=>43],
            ['magacin_id'=>7,'pozicija_id'=>4,'kolicina'=>32],
            ['magacin_id'=>8,'pozicija_id'=>3,'kolicina'=>59],
            ['magacin_id'=>9,'pozicija_id'=>2,'kolicina'=>81],
            ['magacin_id'=>10,'pozicija_id'=>1,'kolicina'=>33],

            ['magacin_id'=>11,'pozicija_id'=>5,'kolicina'=>5],
            ['magacin_id'=>11,'pozicija_id'=>7,'kolicina'=>26],
            ['magacin_id'=>12,'pozicija_id'=>6,'kolicina'=>43],
            ['magacin_id'=>13,'pozicija_id'=>7,'kolicina'=>1],
            ['magacin_id'=>14,'pozicija_id'=>8,'kolicina'=>17],
            ['magacin_id'=>14,'pozicija_id'=>5,'kolicina'=>9],
            ['magacin_id'=>15,'pozicija_id'=>6,'kolicina'=>11],
            ['magacin_id'=>16,'pozicija_id'=>7,'kolicina'=>43],
            ['magacin_id'=>17,'pozicija_id'=>8,'kolicina'=>32],
            ['magacin_id'=>18,'pozicija_id'=>7,'kolicina'=>59],
            ['magacin_id'=>19,'pozicija_id'=>6,'kolicina'=>81],
            ['magacin_id'=>20,'pozicija_id'=>5,'kolicina'=>33],
        ]);
        KorisniciAplikacije::insert([
            [
                'napomena'=>'',
                'korisnici_id'=>3,
                'aplikacija_id'=>1
            ],
            [
                'napomena'=>'',
                'korisnici_id'=>6,
                'aplikacija_id'=>1
            ],
            [
                'napomena'=>'',
                'korisnici_id'=>7,
                'aplikacija_id'=>1
            ],
            [
                'napomena'=>'',
                'korisnici_id'=>8,
                'aplikacija_id'=>1
            ],
            ////////////////////////////
            [
                'napomena'=>'',
                'korisnici_id'=>5,
                'aplikacija_id'=>2
            ],
            [
                'napomena'=>'',
                'korisnici_id'=>9,
                'aplikacija_id'=>2
            ],
            [
                'napomena'=>'',
                'korisnici_id'=>10,
                'aplikacija_id'=>2
            ],
            [
                'napomena'=>'',
                'korisnici_id'=>11,
                'aplikacija_id'=>2
            ]
        ]);

    }

}