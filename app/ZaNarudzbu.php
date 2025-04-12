<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class ZaNarudzbu extends Model{
    protected $table = 'za_narudzbu';
    protected $fillable = ['aktivan','kolicina_porucena','kolicina_pristigla','created_at','updated_at','fakture_id','proizvod_id'];
}