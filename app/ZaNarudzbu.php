<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 3/31/2015
 * Time: 6:37 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class ZaNarudzbu extends Model{
    protected $table = 'za_narudzbu';
    protected $fillable = ['aktivan','kolicina_porucena','kolicina_pristigla','created_at','updated_at','narudzbenice_id','proizvod_id',];
}