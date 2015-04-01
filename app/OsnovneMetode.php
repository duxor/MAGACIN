<?php
/**
 * Created by PhpStorm.
 * User: Dušan
 * Date: 3/30/2015
 * Time: 11:36 PM
 */

namespace App;
use App\Magacin as Skladiste;
use Anouar\Fpdf\Facades\Fpdf;

class OsnovneMetode {
    public static function nestanakProizvoda(){
        return Skladiste::whereRaw('kolicina_stanje<kolicina_min')->where('naruceno','=',0)->get(['id'])->count();
    }
    public static function pdfTabela($header,$data,$imeFajla){
        Fpdf::SetFillColor(255,0,0);
        Fpdf::SetTextColor(255);
        Fpdf::SetDrawColor(128,0,0);
        Fpdf::SetLineWidth(.3);
        
        Fpdf::SetFont('Arial','',14);
        Fpdf::AddPage();
        foreach($header as $col)
            Fpdf::Cell(40,10,$col,'B',0,'C',true);//sirinaKolone,visinaKolone,txt,border[0,1,L,T,R,B],ln[[0],1,2],align[L,C,R],fill[true,false]
        Fpdf::Ln();
        Fpdf::SetTextColor(0);
        foreach($data as $row)
        {
            foreach($row as $col)
                Fpdf::Cell(40,10,$col,1,0,'C',false);
            Fpdf::Ln();
        }
        return Fpdf::Output('pdf/'.$imeFajla.'.pdf','F');
    }
    public static function listaFajlova($folder,$ext){
        return glob("{$folder}/*.{$ext}");
    }
}