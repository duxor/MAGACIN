<?php
use Illuminate\Database\Seeder;
use App\Security;
use App\PravaPristupa;
use App\Korisnici;

class KonfiguracioniPodaci extends Seeder{
    public function run(){
        $pravaPristupa = [
            [
                'naziv' => 'Zabranjen pristup'//1
            ],
            [
                'naziv' => 'Gost'//2
            ],
            [
                'naziv' => 'Analitičar'//3
            ],
            [
                'naziv' => 'Administrator'//4
            ]
        ];
        PravaPristupa::insert($pravaPristupa);

        $korisnici = [
            [//1
                'prezime' => 'Zabranjen',
                'ime' => 'Zabranjen',
                'email' => 'zabrana@zabrana.com',
                'username' => 'zabrana',
                'password' => Security::generateHashPass('zabrana'),
                'prava_pristupa_id' => 1
            ],
            [//2
                'prezime' => 'Administrator',
                'ime' => 'Administrator',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => Security::generateHashPass('admin'),
                'prava_pristupa_id' => 4
            ]
        ];
        Korisnici::insert($korisnici);
    }
}