<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 9/10/2015
 * Time: 10:17 PM
 */

namespace App;
use App\Proizvodi;
///////############################### NE DIRATJ - TEST faza
class AppContent {
    public static function proizvod($id){
        $proizvod=$id?Proizvodi::all()->toArray():null;
        return '<form action="/administracija/proizvod/proizvod" class="form-horizontal" id="forma" method="post">'.
        $proizvod?'<input name="id" value="'.$proizvod['id'].'" hidden="hidden">':''.
        '<style>.fontResize *{font-size: 12px}</style>
        <div class="col-sm-4 fontResize">
            <img style="width: 100%;margin-bottom:20px" src="/img/default/slika-proizvoda.jpg">
            <input name="vrsta_proizvoda_id"'.$proizvod['vrsta_proizvoda_id'].'" class="form-control">
            <div id="dssifra" class="has-feedback">
                <input name="ssifra" value="'.$proizvod['sifra'].'" placeholder="Šifra" class="form-control" id="ssifra">
                <span id="sssifra" class="glyphicon form-control-feedback"></span>
            </div>
            <div id="dnaziv" class="has-feedback">
                <input name="naziv" value="'.$proizvod['naziv'].'" placeholder="Naziv" class="form-control" id="naziv">
                <span id="snaziv" class="glyphicon form-control-feedback"></span>
            </div>
            <div id="dopis" class="has-feedback">
                <textarea name="opis" placeholder="Opis" class="form-control" id="opis">'.$proizvod['opis'].'</textarea>
                <span id="sopis" class="glyphicon form-control-feedback"></span>
            </div>
        </div>
        <div class="col-sm-7 fontResize">
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input name="bar_kod" value="'.$proizvod['bar_kod'].'" placeholder="Bar kod" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input name="proizvodjac" value="'.$proizvod['proizvodjac'].'" placeholder="Proizvođač" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input name="jedinica_mjere" value="'.$proizvod['jedinica_mjere'].'" placeholder="Jedinica mjere" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input name="pakovanje_jedinica_mjere" value="'.$proizvod['pakovanje_jedinica_mjere'].'" placeholder="Pakovanje jedinica mjere" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input name="pakovanje_kolicina" value="'.$proizvod['pakovanje_kolicina'].'" placeholder="Pakovanje kolicina" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button class="btn btn-lg btn-primary" onClick="SubmitForm.submit(\'forma\')" type="button"><span class="glyphicon glyphicon-play-circle"></span> Sačuvaj</button>
                    <button class="btn btn-lg btn-warning" type="reset"><span class="glyphicon glyphicon-refresh"></span> Resetuj unos</button>
                </div>
            </div>
        </div>
    </form>';
    }
}