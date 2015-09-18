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
            ['naziv' => 'AplikativniAdministrator'],//4
            ['naziv' => 'SuperAdministrator']//5
        ]);
        Korisnici::insert([
            [//1
                'prezime' => 'Administrator',
                'ime' => 'Administrator',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => Security::generateHashPass('admin'),
                'prava_pristupa_id' => 5
            ]
        ]);
        Korisnici::insert([
            [//2
                'prezime' => 'Kulić',
                'ime' => 'Radivoje',
                'email' => 'kula63@teol.net',
                'username' => 'eskula',
                'password' => Security::generateHashPass('eskula'),
                'prava_pristupa_id' => 4,
                'naziv' => 'SZTR "KULA" FOČA',
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
                'telefon' => ''
            ]
        ]);
        VrstaFakture::insert([
            ['naziv'=>'Faktura'],//1
            ['naziv'=>'Narudžbenica'],//2
            ['naziv'=>'Predračun'],//3
        ]);
    }
}