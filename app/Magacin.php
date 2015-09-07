<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Magacin extends Model{
    protected $table = 'magacin';
    protected $fillable = ['magacinid_id','proizvod_id','kolicina_stanje','kolicina_min','pozicija_id','naruceno','created_at','updated_at','cijena','rabat','cijena_rabat','aplikacija_id'];
}