<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
class KorisniciAplikacije extends Model{
    protected $table = 'korisnici_aplikacije';
    protected $fillable = ['napomena','created_at','updated_at','korisnici_id','aplikacija_id'];
}