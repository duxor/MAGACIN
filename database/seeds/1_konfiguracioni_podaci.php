<?php
use Illuminate\Database\Seeder;
use App\Security;
use App\PravaPristupa;
use App\Korisnici;
use App\VrstaFakture;

class KonfiguracioniPodaci extends Seeder{
    public function run(){
        PravaPristupa::insert([
            ['naziv' => 'Zabranjen pristup'],//1
            ['naziv' => 'Kupac'],//2
            ['naziv' => 'Dobavljač'],//3
            ['naziv' => 'Radnik'],//4
            ['naziv' => 'AplikativniAdministrator'],//5
            ['naziv' => 'SuperAdministrator']//6
        ]);
        Korisnici::insert([
            [//1
                'prezime' => 'Administrator',
                'ime' => 'Administrator',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => Security::generateHashPass('admin32324322'),
                'prava_pristupa_id' => 6
            ]
        ]);
        Korisnici::insert([
            [//2
                'prezime' => 'Kulić',
                'ime' => 'Radivoje',
                'email' => 'kula63@teol.net',
                'username' => 'eskula',
                'password' => Security::generateHashPass('eskula'),
                'prava_pristupa_id' => 5,
                'adresa' => 'Ul. Svetosavska bb',
                'grad' => '73300 Foča',
                'telefon' => '',
                'foto'=>''
            ],
            [//3
                'prezime' => 'Radnik-Kulin',
                'ime' => 'Radnik',
                'email' => 'petrovic.petar@domen.net',
                'username' => 'radnik',
                'password' => Security::generateHashPass('radnik'),
                'prava_pristupa_id' => 4,
                'adresa' => '',
                'grad' => '',
                'telefon' => '',
                'foto'=>''
            ],
            [//4
                'prezime' => 'Vlasnik',
                'ime' => 'Tester',
                'email' => 'vlasnik.testiranja@domen.net',
                'username' => 'vltester',
                'password' => Security::generateHashPass('vltester'),
                'prava_pristupa_id' => 5,
                'adresa' => '',
                'grad' => '',
                'telefon' => '',
                'foto'=>''
            ],
            [//5
                'prezime' => 'Radnik',
                'ime' => 'Tester',
                'email' => 'radnik.testiranja@domen.net',
                'username' => 'radtester',
                'password' => Security::generateHashPass('radtester'),
                'prava_pristupa_id' => 4,
                'adresa' => '',
                'grad' => '',
                'telefon' => '',
                'foto'=>'/img/aplikacije/testiranje/korisnici/5.jpg'
            ]
        ]);
        VrstaFakture::insert([
            ['naziv'=>'Faktura','slug'=>'faktura'],//1
            ['naziv'=>'Narudžbenica','slug'=>'narudzbenica'],//2
            ['naziv'=>'Predračun','slug'=>'predracun'],//3
            ['naziv'=>'Ulaz','slug'=>'ulaz'],//4
            ['naziv'=>'Početno stanje','slug'=>'inicijalno'],//5
            ['naziv'=>'Razmjena','slug'=>'razmjena'],//6
        ]);
    }
}