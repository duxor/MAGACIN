<?php

use Illuminate\Database\Seeder;
use App\Proizvodi;
use App\MagaciniID;
use App\Pozicija;
use App\Magacin;

class TestPodaci extends Seeder{

    public function run(){

        $proizvodi = [
            [//1
                'sifra'=>'test001',
                'naziv'=>'Mašina za veš',
                'opis'=>'Velika, sa dimenzijama LxMxD',
                'cijena'=>'450'
            ],
            [//2
                'sifra'=>'test002',
                'naziv'=>'Mašina za posuđe',
                'opis'=>'Uspravna, sa velikim poklopcem.',
                'cijena'=>'360'
            ],
            [//3
                'sifra'=>'test003',
                'naziv'=>'Usisivač',
                'opis'=>'Automatski sa robotskim funkcijama.',
                'cijena'=>'240'
            ],
            [//4
                'sifra'=>'test004',
                'naziv'=>'Bojler',
                'opis'=>'Veliki od 200l',
                'cijena'=>'280'
            ],
            [//5
                'sifra'=>'test005',
                'naziv'=>'Kuhinjska napa',
                'opis'=>'Utisnuta, sa velikim kapacitetom i garancijom na 5 godina.',
                'cijena'=>'400'
            ],
            [//6
                'sifra'=>'test007',
                'naziv'=>'Mikser',
                'opis'=>'Ručni, sa 5 funkcionalnih brzina.',
                'cijena'=>'30'
            ],
        ];
        Proizvodi::insert($proizvodi);

        $magacini = [
            [//1
                'naziv'=>'Magacin 1',
                'opis'=>'Magacin u ulici Miloša Obilića 69'
            ],
            [//2
                'naziv'=>'Magacin 2',
                'opis'=>'Magacin u naselju Ratka Mladića'
            ],
            [//3
                'naziv'=>'Magacin 3',
                'opis'=>'Magacin za municiju :)'
            ],
        ];
        MagaciniID::insert($magacini);

        $pozicija = [
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
        ];
        Pozicija::insert($pozicija);

        $magacin = [
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
                'kolicina_min'=>3,
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
                'kolicina_min'=>2,
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
        ];
        Magacin::insert($magacin);

    }

}