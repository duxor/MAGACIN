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
class TestPodaci extends Seeder{

    public function run(){
        Aplikacija::insert([
            [//1
                'naziv'=>'Elektroservis Kula Foča',
                'slug'=>'eskulaf',
                'korisnici_id'=>2,
                'opis'=>'',
                'napomena'=>'',
                'logo'=>''
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
                'foto'=>''
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
                'foto'=>''
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
                'foto'=>''
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
                'foto'=>''
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
                'foto'=>''
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
                'foto'=>''
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
                'opis'=>'Magacin za municiju :)',
                'aplikacija_id'=>1
            ],
        ]);
        Pozicija::insert([
            [//1
                'stolaza'=>'1',
                'polica'=>'1',
                'pozicija'=>'1',
                'opis'=>'Srednja, na početku...',
                'aplikacija_id'=>1
            ],
            [//2
                'stolaza'=>'1',
                'polica'=>'2',
                'pozicija'=>'1',
                'opis'=>'Opis neki 1.....',
                'aplikacija_id'=>1
            ],
            [//3
                'stolaza'=>'2',
                'polica'=>'1',
                'pozicija'=>'3',
                'opis'=>'Opis neki 2.....',
                'aplikacija_id'=>1
            ],
            [//4
                'stolaza'=>'2',
                'polica'=>'1',
                'pozicija'=>'5',
                'opis'=>'Opis neki 3.....',
                'aplikacija_id'=>1
            ],
        ]);
        Magacin::insert([
            [
                'magacin_id_id'=>1,
                'proizvod_id'=>1,
                'kolicina_stanje'=>7,
                'kolicina_min'=>3,
                'pozicija_id'=>1,
                'cijena'=>400
            ],
            [
                'magacin_id_id'=>1,
                'proizvod_id'=>2,
                'kolicina_stanje'=>12,
                'kolicina_min'=>5,
                'pozicija_id'=>2,
                'cijena'=>600
            ],
            [
                'magacin_id_id'=>1,
                'proizvod_id'=>3,
                'kolicina_stanje'=>15,
                'kolicina_min'=>21,
                'pozicija_id'=>4,
                'cijena'=>300
            ],
            [
                'magacin_id_id'=>2,
                'proizvod_id'=>5,
                'kolicina_stanje'=>56,
                'kolicina_min'=>23,
                'pozicija_id'=>1,
                'cijena'=>200
            ],
            [
                'magacin_id_id'=>2,
                'proizvod_id'=>1,
                'kolicina_stanje'=>32,
                'kolicina_min'=>12,
                'pozicija_id'=>2,
                'cijena'=>500
            ],
            [
                'magacin_id_id'=>2,
                'proizvod_id'=>3,
                'kolicina_stanje'=>44,
                'kolicina_min'=>12,
                'pozicija_id'=>3,
                'cijena'=>400
            ],
            [
                'magacin_id_id'=>3,
                'proizvod_id'=>1,
                'kolicina_stanje'=>34,
                'kolicina_min'=>25,
                'pozicija_id'=>1,
                'cijena'=>340
            ],
            [
                'magacin_id_id'=>3,
                'proizvod_id'=>2,
                'kolicina_stanje'=>3,
                'kolicina_min'=>7,
                'pozicija_id'=>2,
                'cijena'=>500
            ],
            [
                'magacin_id_id'=>3,
                'proizvod_id'=>3,
                'kolicina_stanje'=>5,
                'kolicina_min'=>2,
                'pozicija_id'=>3,
                'cijena'=>450
            ],
            [
                'magacin_id_id'=>3,
                'proizvod_id'=>4,
                'kolicina_stanje'=>6,
                'kolicina_min'=>3,
                'pozicija_id'=>4,
                'cijena'=>200
            ],
        ]);
        Korisnici::insert([
            [//dobavljac
                'prezime' => 'Petrović',
                'ime' => 'Petar',
                'email' => 'petrovic.petar@petrovo.com',
                'username' => 'petar',
                'password' => Security::generateHashPass('petar'),
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
                'telefon' => '058/211-999'
            ],
            [//kupac - fizicko lice
                'prezime' => 'Marković',
                'ime' => 'Marko',
                'email' => 'markovicc@gmail.com',
                'username' => 'marko',
                'password' => Security::generateHashPass('marko'),
                'prava_pristupa_id' => 1,//zabranjen
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
                'telefon' => '065/290-331'
            ]
        ]);

    }

}