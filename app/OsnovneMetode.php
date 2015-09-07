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
use PDF;

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
            Fpdf::Cell(40,10,iconv('UTF-8', 'ASCII//TRANSLIT', $col),'B',0,'C',true);//sirinaKolone,visinaKolone,txt,border[0,1,L,T,R,B],ln[[0],1,2],align[L,C,R],fill[true,false]
        Fpdf::Ln();
        Fpdf::SetTextColor(0);
        foreach($data as $row)
        {
            foreach($row as $col)
                Fpdf::Cell(40,10,iconv('UTF-8', 'ASCII//TRANSLIT', $col),1,0,'C',false);
            Fpdf::Ln();
        }
        return Fpdf::Output('pdf/'.$imeFajla.'.pdf','F');
    }
    public static function listaFajlova($folder,$ext){
        return glob("{$folder}/*.{$ext}");
    }
    public static function faktura(){
        //osnovne
        Pdf::setMargins(10,35,10,true);
        Pdf::SetAutoPageBreak(true, 20);
        //informacije o dokumentu
        Pdf::SetCreator('Dusan IS');
        Pdf::SetAuthor('Dušan Perišić');
        Pdf::SetTitle('Test verzija Foča');
        Pdf::SetSubject('Naslov Subject Foča');
        Pdf::SetKeywords('Ključne riječi');
        //HEADER
        //dd(K_PATH_IMAGES);
        Pdf::setHeaderFont(['freeserif','B',14],['freeserif','B',11]);
        Pdf::setHeaderMargin(10);
        Pdf::setHeaderData('img/kula-logo.jpg', 40, 'SZTR "KULA" FOČA', "Ul. Svetosavska bb\n73300 Foča");
        //GLAVNI DIO
        Pdf::SetFont('freeserif','',10);
        Pdf::AddPage();
        Pdf::writeHTMLCell(0, 0, '', '', '
            <style>
                table .prodavackupac{width: 40%}
                table tr .d1{width:32%}
                table tr .d2{width:60%}
                .proizvodi{border-top: 2.5px solid black}
                .proizvodi tr td{border-bottom: 0.1px dashed black;border-right: 1.5px solid black}
                .proizvodi .header{border-bottom: 1.5px solid black}
                .proizvodi .topborder{border-top: 1.5px solid black}
            </style>
            <table class="prodavackupac">
                <tr><td>
                    <table>
                        <tr><td class="d1">JIB:</td><td class="d2">4503782250007</td></tr>
                        <tr><td class="d1">PDV:</td><td class="d2">503782250007</td></tr>
                        <tr><td class="d1">Žiro Rn:</td><td class="d2">551-404-11288935-39</td></tr>
                        <tr><td class="d1">Banka:</td><td class="d2">Uni Credit Bank</td></tr>
                        <tr><td class="d1">Žiro Rn:</td><td class="d2">562-006-00002081-69</td></tr>
                        <tr><td class="d1">Banka:</td><td class="d2">Razvojna banka Foča</td></tr>
                        <tr><td class="d1">Registracija:</td><td class="d2">Opstina Foča</td></tr>
                        <tr><td class="d1">Br. upisa:</td><td class="d2">05-350-47</td></tr>
                    </table>
                </td>
                <td>
                    <b style="font-size: 130%">Kupac:</b>
                    <br>
                    <table>
                        <tr><td class="d1">Naziv:</td><td class="d2">Kupac</td></tr>
                        <tr><td class="d1">JIB:</td><td class="d2">1111111111111111</td></tr>
                        <tr><td class="d1">PDV:</td><td class="d2">1111111111111</td></tr>
                        <tr><td class="d1">Žiro Rn:</td><td class="d2">11111111111</td></tr>
                        <tr><td class="d1">Banka:</td><td class="d2">Uni Credit Bank</td></tr>
                        <tr><td class="d1">Žiro Rn:</td><td class="d2">1111111119</td></tr>
                        <tr><td class="d1">Banka:</td><td class="d2">Razvojna banka Foča</td></tr>
                        <tr><td class="d1">Registracija:</td><td class="d2">Opstina Foča</td></tr>
                        <tr><td class="d1">Br. upisa:</td><td class="d2">1111112</td></tr>
                    </table>
                </td>
                </tr>
            </table>
            <br>

            <p><b>Datum: <u>'.date('d.m.Y.').'</u></b></p>
            <h2>Faktura br. ____________</h2>
            <p>Na osnovu: .........................................................................................................................................<br>Plaćanje: ............................................................................................................................................</p>

            <table class="proizvodi" align="center">
                <thead>
                    <tr>
                        <td class="header" style="width:25px;border-left: 2.5px solid black">Red. br.</td>
                        <td class="header" style="width:50px">Kat. broj artikla</td>
                        <td class="header" style="width:195px">OPIS</td>
                        <td class="header" style="width:30px">Kol.</td>
                        <td class="header" style="width:30px">Jed. mjere</td>
                        <td class="header" style="width:50px">Maloprod. cijena</td>
                        <td class="header" style="width:50px">Iznos bez PDV-a</td>
                        <td class="header" style="width:50px">PDV</td>
                        <td class="header" style="width:50px;border-right: 2.5px solid black">Iznos sa PDV-om</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width:25px;border-left: 2.5px solid black">1</td>
                        <td style="width:50px">1324</td>
                        <td style="width:195px">Opis artiklaaaa</td>
                        <td style="width:30px">1</td>
                        <td style="width:30px">Kom</td>
                        <td style="width:50px">1</td>
                        <td style="width:50px">1</td>
                        <td style="width:50px">1</td>
                        <td style="width:50px;border-right: 2.5px solid black">1</td>
                    </tr>
                    <tr>
                        <td style="width:25px;border-left: 2.5px solid black">2</td>
                        <td style="width:50px">21231</td>
                        <td style="width:195px">Opis artiklaaaa broj dva</td>
                        <td style="width:30px">1</td>
                        <td style="width:30px">Kom</td>
                        <td style="width:50px">1</td>
                        <td style="width:50px">1</td>
                        <td style="width:50px">1</td>
                        <td style="width:50px;border-right: 2.5px solid black">1</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" rowspan="10" style="border-top: 2.5px solid black;border-bottom: none;border-right: none;text-align:left"><b>Napomena:</b>
                            <br>Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu. Napomena uz fakturu.
                        </td>
                        <td colspan="3" style="border-left: 2.5px solid black;border-top: 1.5px solid black;border-bottom:none">Ukupan iznos bez PDV-а</td>
                        <td colspan="2" style="border-right: 2.5px solid black;border-top: 1.5px solid black;border-bottom:none"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-left: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none">PDV 17%</td>
                        <td colspan="2" style="border-right: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-left: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none">Ukupan iznos sa PDV-om</td>
                        <td colspan="2" style="border-right: 2.5px solid black;border-top: 0.1px solid black;border-bottom:none"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-left: 2.5px solid black;border-bottom: 2.5px solid black;border-top: 0.1px solid black"><b>Ukupan iznos za uplatu (KM)</b></td>
                        <td colspan="2" style="border-right: 2.5px solid black;border-bottom: 2.5px solid black;border-top: 0.1px solid black"></td>
                    </tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"><b>Potpis i pečat</b></td></tr>
                    <tr><td style="border-bottom:none;border-right:none"></td><td colspan="3" style="border-bottom:0.1px solid black;border-right:none"></td><td style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="5" style="border-bottom:none;border-right:none"></td></tr>

                    <tr><td colspan="9" style="border-bottom:none;border-right:none"></td></tr>
                    <tr><td colspan="9" style="border-bottom:none;border-right:none;text-align:left">Reklamacije se uvažavaju u roku od 8 dana po prijemu robe i usluge</td></tr>
                    <tr><td colspan="9" style="border-bottom:none;border-right:none;text-align:left">Za sve sporove nadležan je Osnovni sud u Foči</td></tr>
                    <tr><td colspan="9" style="border-bottom:none;border-right:none;text-align:right">Hvala na povjerenju!</td></tr>
                </tfoot>
            </table>
        ', 0, 1, 0, true, '', true);
        //Pdf::Write(0, 'SZTR "KULA" FOČA');
        Pdf::Output('faktura.pdf');
        exit;
    }
}