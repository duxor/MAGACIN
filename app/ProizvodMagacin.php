<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
class ProizvodMagacin extends Model{
    protected $table = 'proizvod_iz_magacina';
    protected $fillable = ['napomena','created_at','updated_at','za_narudzbu_id'];
}