<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class ProizvodiIzMagacina extends Model{
    protected $table='proizvod_iz_magacina';
    protected $fillable=['napomena','za_narudzbu_id','magacin_id','kolicina'];
}