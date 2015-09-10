<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 3/30/2015
 * Time: 2:07 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Pozicija extends Model{
    protected $table = 'pozicija';
    protected $fillable = ['stolaza','polica','pozicija','opis','created_at','updated_at','aplikacija_id'];
}