<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
class Fakture extends Model{
    protected $table = 'fakture';
    protected $fillable = ['datum_narudzbe','datum_isporuke','potvrda','created_at','updated_at','vrsta_fakture_id','broj_fakture','aplikacija_id','korisnici_aplikacije_id','pdf_link'];
}