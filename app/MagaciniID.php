<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 3/30/2015
 * Time: 11:39 AM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class MagaciniID extends Model{
    protected $table = 'magacinid';
    protected $fillable = ['naziv','opis','created_at','updated_at'];
}