<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 3/31/2015
 * Time: 6:35 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Narudzbenice extends Model{
    protected $table = 'narudzbenice';
    protected $fillable = ['datum_narudzbe','datum_isporuke','created_at','updated_at'];
}