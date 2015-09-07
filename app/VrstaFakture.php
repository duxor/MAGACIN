<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
class VrstaFakture extends Model{
    protected $table = 'vrsta_fakture';
    protected $fillable = ['naziv','napomena','created_at','updated_at'];
}