<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Magacin extends Model{
    protected $table = 'magacin';
    protected $fillable = ['magacin_id_id','proizvod_id','kolicina_stanje','kolicina_min','pozicija_id','naruceno','created_at','updated_at','cijena','rabat','cijena_rabat'];
}