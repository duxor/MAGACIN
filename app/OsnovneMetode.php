<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 3/30/2015
 * Time: 11:36 PM
 */

namespace App;
use App\Magacin as Skladiste;

class OsnovneMetode {
    public static function nestanakProizvoda(){
        return Skladiste::whereRaw('kolicina_stanje<kolicina_min')->get(['id'])->count();
    }
}