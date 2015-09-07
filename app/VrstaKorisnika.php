<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 8/21/2015
 * Time: 2:09 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class VrstaKorisnika extends Model{
    protected $table = 'vrsta_korisnika';
    protected $fillable = ['naziv','created_at','updated_at'];
}