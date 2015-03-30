<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 2/25/2015
 * Time: 11:00 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Korisnici extends  Model{
    protected $table = 'korisnici';
    protected $fillable = ['prezime', 'ime', 'username', 'password', 'email', 'token', 'rezervacija', 'created_at', 'updated_at', 'pravaPristupa_id'];
}