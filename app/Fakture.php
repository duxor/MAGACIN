<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
class Fakture extends Model{
    protected $table = 'fakture';
    protected $fillable = ['datum_narudzbe','datum_isporuke','potvrda','created_at','updated_at','vrsta_fakture_id','broj_fakture','aplikacija_id','korisnici_aplikacije_id','pdf_link','ukupno_proracun','ukupno_proizvoda'];

    public static function ukloni($id){
        Magaciniranje::join('za_narudzbu as zn','zn.id','=','magaciniranje.za_narudzbu_id')
            ->join('fakture as f','f.id','=','zn.fakture_id')
            ->where('f.aplikacija_id',Session::get('aplikacija_id'))
            ->where('zn.fakture_id',$id)->delete();
        ZaNarudzbu::join('fakture as f','f.id','=','za_narudzbu.fakture_id')
            ->where('f.aplikacija_id',Session::get('aplikacija_id'))
            ->where('za_narudzbu.fakture_id',$id)
            ->delete();
        unlink(substr(Fakture::where('aplikacija_id',Session::get('aplikacija_id'))->where('id',$id)->get(['pdf_link'])->first()->pdf_link,1));
        return Fakture::where('aplikacija_id',Session::get('aplikacija_id'))->where('id',$id)->delete();
    }
}