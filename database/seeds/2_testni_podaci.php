<?php

use Illuminate\Database\Seeder;
use App\Proizvodi;
use App\MagaciniID;
use App\Pozicija;
use App\Magacin;
use App\Korisnici;
use App\Security;
class TestPodaci extends Seeder{

    public function run(){

        Proizvodi::insert([
            [//1
                'sifra'=>'test001',
                'naziv'=>'Mašina za veš',
                'opis'=>'Velika, sa dimenzijama LxMxD',
                'cijena_nabavna'=>'400',
                'cijena_prodajna'=>'450'
            ],
            [//2
                'sifra'=>'test002',
                'naziv'=>'Mašina za posuđe',
                'opis'=>'Uspravna, sa velikim poklopcem.',
                'cijena_nabavna'=>'400',
                'cijena_prodajna'=>'450'
            ],
            [//3
                'sifra'=>'test003',
                'naziv'=>'Usisivač',
                'opis'=>'Automatski sa robotskim funkcijama.',
                'cijena_nabavna'=>'200',
                'cijena_prodajna'=>'240'
            ],
            [//4
                'sifra'=>'test004',
                'naziv'=>'Bojler',
                'opis'=>'Veliki od 200l',
                'cijena_nabavna'=>'250',
                'cijena_prodajna'=>'280'
            ],
            [//5
                'sifra'=>'test005',
                'naziv'=>'Kuhinjska napa',
                'opis'=>'Utisnuta, sa velikim kapacitetom i garancijom na 5 godina.',
                'cijena_nabavna'=>'380',
                'cijena_prodajna'=>'400'
            ],
            [//6
                'sifra'=>'test007',
                'naziv'=>'Mikser',
                'opis'=>'Ručni, sa 5 funkcionalnih brzina.',
                'cijena_nabavna'=>'20',
                'cijena_prodajna'=>'30'
            ],
        ]);

        MagaciniID::insert([
            [//1
                'naziv'=>'Magacin 1',
                'opis'=>'Magacin u ulici Miloša Obilića 69'
            ],
            [//2
                'naziv'=>'Magacin 2',
                'opis'=>'Magacin u naselju Vojvode Putnika'
            ],
            [//3
                'naziv'=>'Magacin 3',
                'opis'=>'Magacin za municiju :)'
            ],
        ]);

        Pozicija::insert([
            [//1
                'stolaza'=>'1',
                'polica'=>'1',
                'pozicija'=>'1',
                'opis'=>'Srednja, na početku...'
            ],
            [//2
                'stolaza'=>'1',
                'polica'=>'2',
                'pozicija'=>'1',
                'opis'=>'Opis neki 1.....'
            ],
            [//3
                'stolaza'=>'2',
                'polica'=>'1',
                'pozicija'=>'3',
                'opis'=>'Opis neki 2.....'
            ],
            [//4
                'stolaza'=>'2',
                'polica'=>'1',
                'pozicija'=>'5',
                'opis'=>'Opis neki 3.....'
            ],
        ]);

        Magacin::insert([
            [
                'magacinid_id'=>1,
                'proizvod_id'=>1,
                'kolicina_stanje'=>7,
                'kolicina_min'=>3,
                'pozicija_id'=>1
            ],
            [
                'magacinid_id'=>1,
                'proizvod_id'=>2,
                'kolicina_stanje'=>12,
                'kolicina_min'=>5,
                'pozicija_id'=>2
            ],
            [
                'magacinid_id'=>1,
                'proizvod_id'=>3,
                'kolicina_stanje'=>15,
                'kolicina_min'=>21,
                'pozicija_id'=>4
            ],
            [
                'magacinid_id'=>2,
                'proizvod_id'=>5,
                'kolicina_stanje'=>56,
                'kolicina_min'=>23,
                'pozicija_id'=>1
            ],
            [
                'magacinid_id'=>2,
                'proizvod_id'=>1,
                'kolicina_stanje'=>32,
                'kolicina_min'=>12,
                'pozicija_id'=>2
            ],
            [
                'magacinid_id'=>2,
                'proizvod_id'=>3,
                'kolicina_stanje'=>44,
                'kolicina_min'=>12,
                'pozicija_id'=>3
            ],
            [
                'magacinid_id'=>3,
                'proizvod_id'=>1,
                'kolicina_stanje'=>34,
                'kolicina_min'=>25,
                'pozicija_id'=>1
            ],
            [
                'magacinid_id'=>3,
                'proizvod_id'=>2,
                'kolicina_stanje'=>3,
                'kolicina_min'=>7,
                'pozicija_id'=>2
            ],
            [
                'magacinid_id'=>3,
                'proizvod_id'=>3,
                'kolicina_stanje'=>5,
                'kolicina_min'=>2,
                'pozicija_id'=>3
            ],
            [
                'magacinid_id'=>3,
                'proizvod_id'=>4,
                'kolicina_stanje'=>6,
                'kolicina_min'=>3,
                'pozicija_id'=>4
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
                'vrsta_korisnika_id' => 3,//dobavljac
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
                'vrsta_korisnika_id' => 1,//kupac - fizicko lice
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