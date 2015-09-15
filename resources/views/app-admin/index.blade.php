@extends('admin-master')

@section('content')
    <p class="col-sm-12">Magacin v1.0</p>
    <br><hr>

    <div class="col-sm-4 form-inline">
        <button class="btn btn-sm btn-primary" style="margin-left: 3px;padding:3px 10px" onclick="pretraga('')" data-toggle="tooltip" title="Prikaži sve proizvode"><i class="glyphicon glyphicon-lamp"></i></button>
        <button class="btn btn-sm btn-danger" style="margin-left: 3px;padding:3px 10px" onclick="pretraga('',true)" data-toggle="tooltip" title="Prikaži proizode pri isteku zaliha"><i class="glyphicon glyphicon-alert"></i></button>
        <br clear="all"><hr class="col-sm-7"><br clear="all">
        <h3>Pretraga proizvoda</h3>
        <input class="form-control" name="pretraga" id="pretraga" style="width: 55%">
        <button class="btn btn-sm btn-default" style="margin-left: 3px;padding:3px 10px" onclick="pretraga()"><i class="glyphicon glyphicon-search"></i></button>
        <hr class="col-sm-7"><br clear="all">
        <div id="korpa">
            <h3>
                <i class="glyphicon glyphicon-shopping-cart"></i> Korpa
                <button class="btn btn-xs btn-primary" data-toggle="tooltip" title="Učitaj korpu" onclick="ucitajKorpu()"><i class="glyphicon glyphicon-refresh"></i></button>
                <button id="korpaNAR" style="display: none" class="btn btn-xs btn-success" data-toggle="tooltip" title="Izvrši narudžbu" onclick="kreirajFakturu()"><i class="glyphicon glyphicon-file"></i></button>
                <button id="korpaDEL" style="display: none" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Isprazni korpu" onclick="ukloniIzKorpe('all')"><i class="glyphicon glyphicon-trash"></i></button>
            </h3>
            <div id="uKorpi"></div>
        </div>
    </div>
    <div class="col-sm-8" id="work-place"></div>
    <script>
        $(function(){ucitajKorpu(); pretraga('',true)})
        function pretraga(val,zalihe){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/proizvod/pretraga',{
                _token:'{{csrf_token()}}',
                pretraga:val===undefined?$('#pretraga').val():val,
                zalihe:zalihe
            },function(data){
                var rezultat=JSON.parse(data);
                if(rezultat.length<1){
                    $('#work-place').hide();
                    $('#work-place').html('Nema podataka za navedenu pretragu. Pokušajte sa drugim unosom.');
                    $('#work-place').fadeIn();
                    return;
                }
                var ispis='<style>#tab *{text-align: center}</style>' +
                        '<table id="tab" class="table table-striped table-hover table-condensed">' +
                            '<thead>' +
                                '<tr>' +
                                    '<th>Magacin</th>' +
                                    '<th>Šifra</th>' +
                                    '<th>Proizvod</th>' +
                                    '<th></th>'+
                                    '<th><i class="glyphicon glyphicon-stats" data-toggle="tooltip" title="Količina na stanju"></i></th>' +
                                    '<th><i class="glyphicon glyphicon-resize-horizontal" data-toggle="tooltip" title="Stolaža"></i></th>' +
                                    '<th><i class="glyphicon glyphicon-resize-vertical" data-toggle="tooltip" title="Polica"></i></th>' +
                                    '<th><i class="glyphicon glyphicon-indent-left" data-toggle="tooltip" title="Pozicija"></i></th>' +
                                '</tr>' +
                            '</thead>' +
                        '<tbody>';
                var warning=0;
                for(var i=0; i<rezultat.length; i++) {
                    warning=rezultat[i]['kolicina_stanje']<rezultat[i]['kolicina_min']?'danger':rezultat[i]['kolicina_stanje']==rezultat[i]['kolicina_min']?'warning':null;
                    ispis += '' +
                    '<tr class="'+warning+'">' +
                    '<td><a href="#">' + rezultat[i]['nazivmagacina'] + '</a></td>' +
                    '<td>' + rezultat[i]['sifra'] + '</td>' +
                    '<td>' + rezultat[i]['nazivproizvoda'] + '</td>' +
                    '<td><a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Dodaj u korpu" onclick="dodajUKorpu(this, ' + rezultat[i]['pid'] + ')"><i class="glyphicon glyphicon-check"></i></a>'+
                    '<td>' + rezultat[i]['kolicina_stanje'] + ( warning ? ( '<i class="badge">' + (rezultat[i]['kolicina_stanje']-rezultat[i]['kolicina_min']) + '</i>' ) : '' ) +'</td>' +
                    '<td>' + rezultat[i]['stolaza'] + '</td>' +
                    '<td>' + rezultat[i]['polica'] + '</td>' +
                    '<td>' + rezultat[i]['pozicija'] + '</td>' +
                    '</tr>';
                }
                ispis+='</tbody></table>';
                $('#work-place').hide();
                $('#work-place').html(ispis);
                $('#work-place').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            });
        }
        $(document).keypress(function(e){ if(e.which == 13) pretraga();})
        function dodajUKorpu(dugme,id){
            $(dugme).html('<i class="icon-spin6 animate-spin"></i>');
            $.post('/administracija/proizvod/dodaj-u-korpu',{
                _token:'{{csrf_token()}}',
                id:id
            },function(data){console.log(data);
                ucitajKorpu();
                $(dugme).html('<i class="glyphicon glyphicon-check"></i>');
            })
        }
        function ucitajKorpu(){
            $('#uKorpi').html('<center><i class="icon-spin6 animate-spin"></i></center>');
            $.post('/administracija/proizvod/ucitaj-korpu',{
                _token:'{{csrf_token()}}'
            },function(data){
                var rezultat=JSON.parse(data), i = 1, ispis='';
                if(rezultat){
                    $('#korpaNAR').show();
                    $('#korpaDEL').show();
                    ispis+='<table class="table table-condensed" style="font-size: 80%">';
                    $.each(rezultat, function(index, value) {
                        ispis+='<tr><td>'+i+'</td><td>'+value['sifra']+'</td><td>'+value['naziv']+'</td><td><button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Ukloni iz korpe" onclick="ukloniIzKorpe('+index+')"><i class="glyphicon glyphicon-trash"></i></button></td></tr>';
                        i++;
                    });
                    ispis+='</table>';
                }
                else{
                    $('#korpaNAR').hide();
                    $('#korpaDEL').hide();
                    ispis='Nema proizvoda u korpi.';
                }
                $('#uKorpi').hide();
                $('#uKorpi').html(ispis);
                $('#uKorpi').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
            })
        }
        function ukloniIzKorpe(i){
            $('#uKorpi').html('<center><i class="icon-spin6 animate-spin"></i></center>');
            $.post('/administracija/proizvod/ukloni-iz-korpe',{
                _token:'{{csrf_token()}}',
                i:i
            },function(data){
                ucitajKorpu();
            })
        }
        function kreirajFakturu(){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/ucitaj-podatke-za-fakturu',{
                _token:'{{csrf_token()}}'
            },function(data){
                var rezultat=JSON.parse(data),ispis='';
                ispis+='<style>#forma *{font-size: 13px}</style>' +
                '<div id="forma">' +
                    '<div class="col-sm-6">' +
                        '<h3><b><u>Moji podaci</u></b></h3>' +
                        '<table class="">' +
                            '<tr><td>Naziv</td><td><b>' + rezultat.podaci['naziv'] + '</b></td></tr>' +
                            '<tr><td>Adresa</td><td><b>' + rezultat.podaci['adresa'] + '</b></td></tr>' +
                            '<tr><td></td><td><b>' + rezultat.podaci['grad'] + '</b></td></tr>' +
                            (rezultat.podaci['jib']?'<tr><td>JIB</td><td><b>' + rezultat.podaci['jib'] + '</b></td></tr>':'') +
                            (rezultat.podaci['pib']?'<tr><td>PIB</td><td><b>' + rezultat.podaci['pib'] + '</b></td></tr>':'') +
                            (rezultat.podaci['pdv']?'<tr><td>PDV</td><td><b>' + rezultat.podaci['pdv'] + '</b></td></tr>':'') +
                            (rezultat.podaci['banka_1']?'<tr><td>Banka</td><td><b>' + rezultat.podaci['banka_1'] + '</b></td></tr>':'') +
                            (rezultat.podaci['ziro_racun_1']?'<tr><td>Žiro račun</td><td><b>' + rezultat.podaci['ziro_racun_1'] + '</b></td></tr>':'') +
                            (rezultat.podaci['banka_2']?'<tr><td>Banka</td><td><b>' + rezultat.podaci['banka_2'] + '</b></td></tr>':'') +
                            (rezultat.podaci['ziro_racun_2']?'<tr><td>Žiro račun</td><td><b>' + rezultat.podaci['ziro_racun_2'] + '</b></td></tr>':'') +
                            '<tr><td>Registracija</td><td><b>' + rezultat.podaci['registracija'] + '</b></td></tr>' +
                            '<tr><td>Broj upisa</td><td><b>' + rezultat.podaci['broj_upisa'] + '</b></td></tr>' +
                        '</table>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<h3><b><u id="vrsta_korisnika">Kupac</u></b> <b><u><button id="promijeniVrstuKorisnika" class="btn btn-xs btn-primary" onclick="promijeniVrstuKorisnika()"><i class="glyphicon glyphicon-transfer"></i></button></u></b></h3>' +
                        '<input id="vrstaKorisnika" name="vrstaKorisnika" value="2" hidden="hidden">'+
                        '<div id="dobavljac_div">' +
                            '<input name="pretrazi_korisnika" class="form-control" onkeyup="nadjiKorisnika()" placeholder="naziv, prezime, ime, jmbg">' +
                            '<div id="pretraga_div"></div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-12 form-horizontal" style="margin-top:30px">' +
                        '<div class="form-group">' +
                            '<label class="col-sm-3">Datum:</label>' +
                            '<div class="col-sm-8"><input type="date" id="datum" name="datum" class="form-control" disabled></div><button id="datePicker" class="btn btn-primary"><i class="glyphicon glyphicon-calendar"></i></button>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-3">Broj fakture: </label>' +
                            '<div class="col-sm-8"><input name="broj_fakture" class="form-control" disabled></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-3">Na osnovu: </label>' +
                            '<div class="col-sm-8"><input name="na_osnovu" class="form-control"></div>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label class="col-sm-3">Plaćanje: </label>' +
                            '<div class="col-sm-8"><input name="placanje" class="form-control"></div>' +
                        '</div>' +
                    '</div>' +
                    '<div id="tabelaProizvoda" class="col-sm-12">Tabela proizvoda</div>' +
                    '<div class="col-sm-12">' +
                        '<label class="col-sm-3">Napomena: </label>' +
                        '<div class="col-sm-8"><textarea name=napomena" class="form-control" placeholder="Unesite napomenu koja će da bude upisana u fakturu."></textarea>' +
                    '</div>' +
                '</div>';
                $('#work-place').hide();
                $('#work-place').html(ispis);
                $('#work-place').fadeIn();
                $('#datum').datepicker({
                    orientation: "top auto",
                    weekStart: 1,
                    startDate: "current",
                    todayBtn: "linked",
                    toggleActive: true,
                    format: "yyyy-mm-dd"
                });
                var d = new Date();
                $('#datum').datepicker('setDate',new Date());
                $('#datePicker').click(function(){$('#datum').datepicker('show')})
            })
        }
        function nadjiKorisnika(){
            $('#pretraga_div').html('<center><i class="icon-spin6 animate-spin" style="font-size:350%"></i></center>');
            $.post('/administracija/korisnici/ucitaj-korisnike',{
                _token:'{{csrf_token()}}',
                vrsta_korisnika:$('#vrstaKorisnika').val(),
                pretraga:$('[name=pretrazi_korisnika]').val()
            },function(data){
                var ispis='', rezultat=JSON.parse(data);
                if(rezultat){
                    ispis+='<div class="list-group">';
                    $.each(rezultat, function(index, value) {
                        ispis += '' +
                        '<a href="#" class="list-group-item" onclick="izaberiKorisnika(' + value['id'] + ')">' +
                            (value['prezime']?'<b class="list-group-item-heading">' + value['prezime'] + ' ' + value['ime'] + '</b> ':'') +
                            (value['jmbg']?'<b class="list-group-item-heading">' + value['jmbg'] + '</b> ':'') +
                            (value['naziv']?'<b class="list-group-item-heading">' + value['naziv'] + '</b> ':'') +
                            (value['adresa']?'<p class="list-group-item-text" style="text-align: left">' + value['adresa'] + ' ' + value['grad'] + '</p> ':'') +
                            (value['telefon']?'<p class="list-group-item-text" style="text-align: left">' + value['telefon'] + '</p> ':'') +
                        '</a>';
                    });
                    ispis+='</div>';
                }else ispis+='Nema dobavljača u evidenciji. Izvršite dodavanje u podkategoriji korisnika.';
                $('#pretraga_div').html(ispis);
            })
        }
        function izaberiKorisnika(idKorisnika){
            $('#promijeniVrstuKorisnika').hide();
            $('#dobavljac_div').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/korisnici/izaberi-korisnika',{
                _token:'{{csrf_token()}}',
                id:idKorisnika
            },function(data){
                var rezultat=JSON.parse(data);
                $('#dobavljac_div').hide();
                $('#dobavljac_div').html('' +
                '<table class="">' +
                    (rezultat.prezime?'<tr><td>Ime i Prezime</td><td><b>' + rezultat.ime + ' ' + rezultat.prezime + '</b></td></tr>':'') +
                    (rezultat.jmbg?'<tr><td>JMBG</td><td><b>' + rezultat.jmbg + '</b></td></tr>':'') +
                    (rezultat.broj_licne_karte?'<tr><td>Broj lične karte</td><td><b>' + rezultat.broj_licne_karte + '</b></td></tr>':'') +
                    (rezultat.naziv?'<tr><td>Naziv</td><td><b>' + rezultat.naziv + '</b></td></tr>':'') +
                    (rezultat.adresa?'<tr><td>Adresa</td><td><b>' + rezultat.adresa + '</b></td></tr>':'') +
                    (rezultat.grad?'<tr><td></td><td><b>' + rezultat.grad + '</b></td></tr>':'') +
                    (rezultat.jib?'<tr><td>JIB</td><td><b>' + rezultat.jib + '</b></td></tr>':'') +
                    (rezultat.pib?'<tr><td>PIB</td><td><b>' + rezultat.pib + '</b></td></tr>':'') +
                    (rezultat.pdv?'<tr><td>PDV</td><td><b>' + rezultat.pdv + '</b></td></tr>':'') +
                    (rezultat.banka_1?'<tr><td>Banka</td><td><b>' + rezultat.banka_1 + '</b></td></tr>':'') +
                    (rezultat.ziro_racun_1?'<tr><td>Žiro račun</td><td><b>' + rezultat.ziro_racun_1 + '</b></td></tr>':'') +
                    (rezultat.banka_2?'<tr><td>Banka</td><td><b>' + rezultat.banka_2 + '</b></td></tr>':'') +
                    (rezultat.ziro_racun_2?'<tr><td>Žiro račun</td><td><b>' + rezultat.ziro_racun_2 + '</b></td></tr>':'') +
                    (rezultat.registracija?'<tr><td>Registracija</td><td><b>' + rezultat.registracija + '</b></td></tr>':'') +
                    (rezultat.broj_upisa?'<tr><td>Broj upisa</td><td><b>' + rezultat.broj_upisa + '</b></td></tr>':'') +
                '</table>');
                $('#dobavljac_div').fadeIn();
                ucitajTabeluProizvoda();
            })
        }
        function promijeniVrstuKorisnika(){
            var vrk = $('#vrstaKorisnika').val();
            $('#vrstaKorisnika').val(vrk==3?2:3);
            $('#vrsta_korisnika').html(vrk==3?'Kupac':'Dobavljač');
        }
        function ucitajTabeluProizvoda(){
            $('#tabelaProizvoda').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/ucitaj-tabelu-proizvoda',{
                _token:'{{csrf_token()}}',
                vrstaKorisnika:$('#vrstaKorisnika').val()
            },function(data){
                var rezultat=JSON.parse(data), ispis='', i=1;
                ispis='<h3 style="font-size: 130%">Proizvodi</h3><table id="tabelaSaProizvodima" class="table table-striped table-condensed table-hover"><thead>' +
                '<tr>' +
                    '<th>Redni broj</th><th>Šifra proizvoda</th><th>Naziv artikla</th><th>Količina</th><th>Jedinica mjere</th>' +
                    ($('#vrstaKorisnika').val()==2?'<th>Maloprodajna cijena</th><th>Iznos bez PDV-a</th><th>PDV</th><th>Iznos sa PDV-om</th>':'') +
                '</tr><thead><tbody>';
                $.each(rezultat, function(index,value){
                    ispis+='<tr><td>' + i + '</td><td>' + value['sifra'] + '</td><td>' + value['naziv'] + '</td><td><input class="form-control" name="kolicina-' + i + '" style="width:50px" value="1" onkeyup="racunajCijenu(' + i + ',this)"></td><td>' + value['jedinica_mjere'] + '</td>' +
                    ($('#vrstaKorisnika').val()==2?'<td id="maloprodajna_cijena-' + i + '">' + (value['maloprodajna_cijena']).toFixed(2) + '</td>':'') +
                    ($('#vrstaKorisnika').val()==2?'<td id="cijena_bez_pdv-' + i + '">' + (value['maloprodajna_cijena']*0.83).toFixed(2) + '</td>':'') +
                    ($('#vrstaKorisnika').val()==2?'<td id="cijena_pdv-' + i + '">' + (value['maloprodajna_cijena']*0.17).toFixed(2) + '</td>':'') +
                    ($('#vrstaKorisnika').val()==2?'<td id="cijena_sa_pdv-' + i + '">' + (value['maloprodajna_cijena']).toFixed(2) + '</td>':'') +
                    '</tr>';
                    i++;
                });
                ispis+='</tbody>'+
                    ($('#vrstaKorisnika').val()==2?
                        '<tfoot>' +
                        '<tr><td colspan="7" style="text-align: right;">Ukupno bez PDV-a</td><td colspan="2" id="ukupno_bez_pdv"></td></tr>' +
                        '<tr><td colspan="7" style="text-align: right;">PDV 17%</td><td colspan="2" id="ukupno_pdv"></td></tr>' +
                        '<tr><td colspan="7" style="text-align: right;">Ukupno sa PDV-om</td><td colspan="2" id="ukupno_sa_pdv"></td></tr>'+
                        '<tr><td colspan="7" style="text-align: right;">Ukupno za uplatu</td><td colspan="2" id="ukupno_za_ulatu" style="font-weight: bold"></td></tr>' +
                        '</tfoot>':'') +
                    '</table>';
                $('#tabelaProizvoda').hide();
                $('#tabelaProizvoda').html(ispis);
                $('#tabelaProizvoda').fadeIn();

                $('#tabelaSaProizvodima').data('ukupno',i);
                racunajUkupnuCijenu();
            })
        }
        function racunajCijenu(idProizvoda,kolicina){
            var ukupnaCijena=parseFloat($('#maloprodajna_cijena-'+idProizvoda).text())*$(kolicina).val();
            $('#cijena_sa_pdv-'+idProizvoda).html((ukupnaCijena).toFixed(2));
            $('#cijena_bez_pdv-'+idProizvoda).html((ukupnaCijena*0.83).toFixed(2));
            $('#cijena_pdv-'+idProizvoda).html((ukupnaCijena*0.17).toFixed(2));
            racunajUkupnuCijenu();
        }
        function racunajUkupnuCijenu(){
            var ukupno=0;
            for(var i=1; i<$('#tabelaSaProizvodima').data('ukupno'); i++)
                ukupno=(parseFloat(ukupno)+parseFloat($('#cijena_sa_pdv-' + i).text())).toFixed(2);
            $('#ukupno_bez_pdv').html((ukupno*0.83).toFixed(2));
            $('#ukupno_pdv').html((ukupno*0.17).toFixed(2));
            $('#ukupno_sa_pdv').html(ukupno);
            $('#ukupno_za_ulatu').html(ukupno);
        }
    </script>
    <i class="icon-spin6 animate-spin" style="font-size: 1px;color:rgba(0,0,0,0)"></i>
@endsection