<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 2/28/2015
 * Time: 6:25 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class PravaPristupa extends Model{
    protected $table = 'prava_pristupa';
    protected $fillable = ['naziv','created_at', 'updated_at'];
}