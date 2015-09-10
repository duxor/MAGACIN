<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class VrstaProizvoda extends Model{
    protected $table = 'vrsta_proizvoda';
    protected $fillable = ['naziv','napomena','created_at','updated_at','aplikacija_id'];
}