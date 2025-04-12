var fakturisanje={
    vrstaFakture:1,
    vrstaKorisnika:2,
    token:'',
    magaciniranje:{},
    brojProizvoda:0,
    dorada:0,
    setToken:function(t){
        fakturisanje.token=t;
    },
    kreiraj:function(vr){
        fakturisanje.vrstaFakture=vr;
        fakturisanje.vrstaKorisnika=2;
        $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
        $.post('/administracija/faktura/ucitaj-podatke-za-fakturu',{
            _token:fakturisanje.token
        },function(data){
            var rezultat=JSON.parse(data),ispis='';
            ispis+='<style>#forma *{font-size: 13px}</style>' +
            '<div id="forma">' +
                '<div class="col-sm-6">' +
                    '<h3><b><u>'+fakturisanje.tekst.mojiPodaci+'</u></b></h3>' +
                    '<table class="">' +
                        (rezultat['naziv']?'<tr><td>'+fakturisanje.tekst.naziv+'</td><td><b>' + rezultat['naziv'] + '</b></td></tr>':'') +
                        (rezultat['adresa']?'<tr><td>'+fakturisanje.tekst.adresa+'</td><td><b>' + rezultat['adresa'] + '</b></td></tr>':'') +
                        (rezultat['grad']?'<tr><td></td><td><b>' + rezultat['grad'] + '</b></td></tr>':'') +
                        (rezultat['jib']?'<tr><td>'+fakturisanje.tekst.jib+'</td><td><b>' + rezultat['jib'] + '</b></td></tr>':'') +
                        (rezultat['pib']?'<tr><td>'+fakturisanje.tekst.pib+'</td><td><b>' + rezultat['pib'] + '</b></td></tr>':'') +
                        (rezultat['pdv']?'<tr><td>'+fakturisanje.tekst.pdv+'</td><td><b>' + rezultat['pdv'] + '</b></td></tr>':'') +
                        (rezultat['banka_1']?'<tr><td>'+fakturisanje.tekst.banka+'</td><td><b>' + rezultat['banka_1'] + '</b></td></tr>':'') +
                        (rezultat['ziro_racun_1']?'<tr><td>'+fakturisanje.tekst.ziroRacun+'</td><td><b>' + rezultat['ziro_racun_1'] + '</b></td></tr>':'') +
                        (rezultat['banka_2']?'<tr><td>'+fakturisanje.tekst.banka+'</td><td><b>' + rezultat['banka_2'] + '</b></td></tr>':'') +
                        (rezultat['ziro_racun_2']?'<tr><td>'+fakturisanje.tekst.ziroRacun+'</td><td><b>' + rezultat['ziro_racun_2'] + '</b></td></tr>':'') +
                        (rezultat['registracija']?'<tr><td>'+fakturisanje.tekst.registracija+'</td><td><b>' + rezultat['registracija'] + '</b></td></tr>':'') +
                        (rezultat['broj_upisa']?'<tr><td>'+fakturisanje.tekst.brUpisa+'</td><td><b>' + rezultat['broj_upisa'] + '</b></td></tr>':'') +
                        '</table>' +
                    '</div>' +
                    ([1,2,4].indexOf(vr)>-1?//vr!=3
                    '<div class="col-sm-6">' +
                        '<h3><b><u id="vrsta_korisnika">'+fakturisanje.tekst.kupac+'</u></b> <b><u><button id="promijeniVrstuKorisnika" class="btn btn-xs btn-primary" onclick="fakturisanje.funkcije.promijeniVrstuKorisnika()"><i class="glyphicon glyphicon-transfer"></i></button></u></b></h3>' +
                        '<div id="dobavljac_div">' +
                            '<input name="pretrazi_korisnika" class="form-control" onkeyup="fakturisanje.funkcije.nadjiKorisnika()" placeholder="'+fakturisanje.tekst.naziv+', '+fakturisanje.tekst.prezime+', '+fakturisanje.tekst.ime+', '+fakturisanje.tekst.jmbg+'">' +
                            '<div id="pretraga_div"></div>' +
                        '</div>' +
                    '</div>':'') +
                    '<div class="col-sm-12 form-horizontal" style="margin-top:30px">' +
                        '<div class="form-group">' +
                            '<label class="col-sm-3">'+fakturisanje.tekst.datum+':</label>' +
                            '<div class="col-sm-8"><input type="date" id="datum" name="datum" class="form-control" disabled></div>'+(vr!=3?'<button id="datePicker" class="btn btn-primary"><i class="glyphicon glyphicon-calendar"></i></button>':'') +
                        '</div>' +
                            '<div class="form-group" id="broj_fakture" style="display: none">' +
                            '<label class="col-sm-3" id="label_broj_fakture">'+fakturisanje.tekst.brFakture+': </label>' +
                        '<div class="col-sm-8"><input name="broj_fakture" class="form-control" value="" data-broj_fakture="" disabled></div>' +
                    '</div>' +
                    (vr!=3?'' +
                    '<div class="form-group">' +
                        '<label class="col-sm-3">'+fakturisanje.tekst.naOsnovu+': </label>' +
                        '<div class="col-sm-8"><input name="na_osnovu" class="form-control"></div>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label class="col-sm-3">'+fakturisanje.tekst.placanje+': </label>' +
                        '<div class="col-sm-8"><input name="placanje" class="form-control"></div>' +
                    '</div>' +
                '</div>':'') +
                '<div id="tabelaProizvoda" class="col-sm-12"></div>' +
                '<div class="form-group">' +
                    '<label class="col-sm-3">'+fakturisanje.tekst.napomena+': </label>' +
                    '<div class="col-sm-8"><textarea name="napomena" class="form-control" placeholder="'+fakturisanje.tekst.napomenaPlaceholder+'"></textarea></div>' +
                '</div>' +
                '<div id="poruka" class="col-sm-12"></div>'+
                '<div class="col-sm-offset-3 col-sm-8">' +
                    '<button id="izvrsiFakturu" style="display:none;margin-top:30px" class="btn btn-primary" onclick="fakturisanje.funkcije.pripremiFakturu()"><i class="glyphicon glyphicon-floppy-disk"></i> '+fakturisanje.tekst.pripremiZaIzvrsenje+'</button>' +
                    '<button id="fakturisi" style="display:none;margin-top:30px" class="btn btn-warning" onclick="fakturisanje.funkcije.zavrsnaFaza()"><i class="glyphicon glyphicon-floppy-disk"></i> '+fakturisanje.tekst.izvrsi+'</button>' +
                    '<button id="otkazi" style="display:none;margin:30px 0 0 5px" class="btn btn-danger" onclick="fakturisanje.funkcije.kreiraj()"><i class="glyphicon glyphicon-off"></i> '+fakturisanje.tekst.otkazi+'</button>' +
                '</div>' +
            '</div>';
            $('#work-place').hide();
            $('#work-place').html(ispis);
            $('#work-place').fadeIn();
            $('#datum').datepicker({orientation: "top auto",weekStart: 1,startDate: "current",todayBtn: "linked",toggleActive: true,format: "yyyy-mm-dd",autoclose: true});
            $('#datum').datepicker('setDate',new Date());
            $('#datePicker').click(function(){$('#datum').datepicker('show')})
            if([3,5].indexOf(vr)>-1) fakturisanje.funkcije.ucitajTabeluProizvoda();
            if(vr==4){fakturisanje.funkcije.promijeniVrstuKorisnika();$('#promijeniVrstuKorisnika').remove()}
        })
    },
    funkcije:{
        ucitajTabeluProizvoda:function(){
            $('#tabelaProizvoda').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/faktura/ucitaj-tabelu-proizvoda',{
                _token:fakturisanje.token,
                vrstaKorisnika:parseInt(fakturisanje.vrstaKorisnika),
                vrstaFakture:parseInt(fakturisanje.vrstaFakture)
            },function(data){
                var rezultat=JSON.parse(data), ispis='', i=0;
                ispis='<style>#tabelaSaProizvodima *{text-align:center}</style><table id="tabelaSaProizvodima" class="table table-striped table-condensed table-hover"><thead>' +
                '<tr>' +
                '<th>'+fakturisanje.tekst.redniBr+'</th><th>'+fakturisanje.tekst.sifraProizvoda+'</th><th>'+fakturisanje.tekst.nazivArtikla+'</th><th>'+fakturisanje.tekst.kolicina+'</th><th>'+fakturisanje.tekst.jedinicaMjere+'</th>' +
                ([1,3].indexOf(fakturisanje.vrstaFakture)>-1?'<th>'+fakturisanje.tekst.maloprodajnaCijena+'</th><th>'+fakturisanje.tekst.iznosBezPdv+'</th><th>'+fakturisanje.tekst.pdv+'</th><th>'+fakturisanje.tekst.iznosSaPdv+'</th>':'') +
                '</tr><thead><tbody>';

                if([1,4,5].indexOf(fakturisanje.vrstaFakture)>-1){ fakturisanje.magaciniranje={}; fakturisanje.magaciniranje.proizvodi=[] }
                fakturisanje.brojProizvoda=0;
                $.each(rezultat, function(index,value){
                    fakturisanje.brojProizvoda++;
                    ispis+='<tr><td>' + (index+1) + '</td><td>' + value['sifra'] + '</td><td>' + value['naziv'] + '</td><td>';

                    if([1,4,5].indexOf(fakturisanje.vrstaFakture)>-1){//za potrebe magaciniranja
                        fakturisanje.magaciniranje.proizvodi[index]={
                            sifra:value['sifra'],
                            naziv:value['naziv']
                        };
                    }

                    if(fakturisanje.vrstaFakture==1) { //ogranicenje broja proizvoda na broj sa stanja
                        ispis+='<select class="form-control" name="kolicina-' + index + '" style="width:80px" value="1" onchange="fakturisanje.funkcije.racunajCijenu(' + index + ',this)">';
                        for (var i = 0; i < value['ukupno_na_stanju']; i++)
                            ispis += '<option value="' + i + '" '+(i==1?'selected':'')+'>' + i + '</option>';
                        ispis += '</select>';
                    }else ispis+='<input class="form-control" name="kolicina-' + index + '" style="width:50px" value="1" onkeyup="fakturisanje.funkcije.racunajCijenu(' + index + ',this)">';

                    ispis+='</td><td>' + value['jedinica_mjere'] + '</td>' +
                    ([1,3].indexOf(fakturisanje.vrstaFakture)>-1?'<td id="maloprodajna_cijena-' + index + '">' + (value['maloprodajna_cijena']).toFixed(2) + '</td>' +
                    '<td id="cijena_bez_pdv-' + index + '">' + (value['maloprodajna_cijena']*0.83).toFixed(2) + '</td>' +
                    '<td id="cijena_pdv-' + index + '">' + (value['maloprodajna_cijena']*0.17).toFixed(2) + '</td>' +
                    '<td id="cijena_sa_pdv-' + index + '">' + (value['maloprodajna_cijena']).toFixed(2) + '</td>':'') +
                    '</tr>';
                });
                ispis+='</tbody>'+
                ([1,3].indexOf(fakturisanje.vrstaFakture)>-1?
                '<tfoot>' +
                '<tr><td colspan="7" style="text-align: right;">'+fakturisanje.tekst.ukupnoBezPdv+'</td><td colspan="2" id="ukupno_bez_pdv"></td></tr>' +
                '<tr><td colspan="7" style="text-align: right;">'+fakturisanje.tekst.iznosPdv+'</td><td colspan="2" id="ukupno_pdv"></td></tr>' +
                '<tr><td colspan="7" style="text-align: right;">'+fakturisanje.tekst.ukupnoSaPdv+'</td><td colspan="2" id="ukupno_sa_pdv"></td></tr>'+
                '<tr><td colspan="7" style="text-align: right;">'+fakturisanje.tekst.ukupnoZaUplatu+'</td><td colspan="2" id="ukupno_za_ulatu" style="font-weight: bold"></td></tr>' +
                '</tfoot>':'') +
                '</table>';
                $('#tabelaProizvoda').hide();
                $('#tabelaProizvoda').html(ispis);
                $('#tabelaProizvoda').fadeIn();
                if([1,3].indexOf(fakturisanje.vrstaFakture)>-1) fakturisanje.funkcije.racunajUkupnuCijenu();
                $('#label_broj_fakture').html((fakturisanje.vrstaFakture==1?'Faktura':fakturisanje.vrstaFakture==2?'Narudžbenica':'Predračun')+' broj:');
                $('#izvrsiFakturu').fadeIn();
            })
        },
        racunajCijenu:function(index,kolicina){
            var ukupnaCijena=parseFloat($('#maloprodajna_cijena-'+index).text())*$(kolicina).val();
            $('#cijena_sa_pdv-'+index).html((ukupnaCijena).toFixed(2));
            $('#cijena_bez_pdv-'+index).html((ukupnaCijena*0.83).toFixed(2));
            $('#cijena_pdv-'+index).html((ukupnaCijena*0.17).toFixed(2));
            fakturisanje.funkcije.racunajUkupnuCijenu();
        },
        racunajUkupnuCijenu:function(){
            var ukupno=0;
            for(var i=0; i<fakturisanje.brojProizvoda; i++)
                ukupno=(parseFloat(ukupno)+parseFloat($('#cijena_sa_pdv-' + i).text())).toFixed(2);
            $('#ukupno_bez_pdv').html((ukupno*0.83).toFixed(2));
            $('#ukupno_pdv').html((ukupno*0.17).toFixed(2));
            $('#ukupno_sa_pdv').html(ukupno);
            $('#ukupno_za_ulatu').html(ukupno);
        },
        pripremiFakturu:function(){
            $('#poruka').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $('#izvrsiFakturu').remove();
            var faktura={}; faktura.proizvodi=[];
            for(var i=0; i<fakturisanje.brojProizvoda; i++){
                if([1,3].indexOf(fakturisanje.vrstaFakture)>-1)
                    faktura.proizvodi[i]={
                        kolicina:parseInt($('[name=kolicina-'+i+']').val()),
                        cijena_sa_pdv:parseFloat($('#cijena_sa_pdv-'+i).text()),
                        cijena_bez_pdv:parseFloat($('#cijena_bez_pdv-'+i).text()),
                        cijena_pdv:parseFloat($('#cijena_pdv-'+i).text())
                    };
                else faktura.proizvodi[i]={ kolicina:parseInt($('[name=kolicina-'+i+']').val()) }
                $('[name=kolicina-'+i+']').closest('td').text(faktura.proizvodi[i].kolicina);

                if([1,4,5].indexOf(fakturisanje.vrstaFakture)>-1){//za potrebe magaciniranja
                    fakturisanje.magaciniranje.proizvodi[i].kolicina=faktura.proizvodi[i].kolicina;
                }
            }
            if([1,3].indexOf(fakturisanje.vrstaFakture)>-1)
                faktura.ukupno={
                    ukupno_sa_pdv:parseFloat($('#ukupno_sa_pdv').text()),
                    ukupno_bez_pdv:parseFloat($('#ukupno_bez_pdv').text()),
                    ukupno_pdv:parseFloat($('#ukupno_pdv').text())
                }
            faktura.datum=$('#datum').val(); var d=new Date(faktura.datum); $('#datum').closest('div').html(d.getDate()+'.'+(d.getMonth()+1)+'.'+ d.getFullYear()+'.'); $('#datePicker').remove();
            if(fakturisanje.vrstaFakture!=3) {
                faktura.na_osnovu = $('[name=na_osnovu]').val();
                if(faktura.na_osnovu) $('[name=na_osnovu]').closest('div').html(faktura.na_osnovu);
                else $('[name=na_osnovu]').closest('div').closest('.form-group').remove();
                faktura.placanje = $('[name=placanje]').val();
                if(faktura.placanje) $('[name=placanje]').closest('div').html(faktura.placanje);
                else $('[name=placanje]').closest('div').closest('.form-group').remove();
            }
            faktura.napomena = $('[name=napomena]').val();
            $('[name=napomena]').closest('div').html(faktura.napomena);
            $.post('/administracija/faktura/pripremi-fakturu',{
                _token:fakturisanje.token,
                faktura:JSON.stringify(faktura)
            },function(data){
                var rezultat=JSON.parse(data);
                $('[name=broj_fakture]').closest('div').html(rezultat.broj_fakture+'/'+(new Date(faktura.datum)).getFullYear());
                faktura=null;
                $('[name=broj_fakture]').val();
                $('#broj_fakture').fadeIn();
                $('#poruka').hide();
                $('#poruka').html('<div class="alert alert-warning" style="font-size:18px">'+fakturisanje.tekst.akcijaJeSpremna + (fakturisanje.vrstaKorisnika==2?' '+fakturisanje.tekst.akcijomCeBitiAzurirano:'') + ' '+fakturisanje.tekst.provjeritePodatkeIzvrsi+'</div>');
                $('#poruka').fadeIn();
                $('#fakturisi').fadeIn();
                $('#otkazi').fadeIn();
            });
        },
        zavrsnaFaza:function(){
            if([1,4,5].indexOf(fakturisanje.vrstaFakture)>-1) fakturisanje.funkcije.magaciniraj();
            else fakturisanje.funkcije.zavrsiFakturisanje();
        },
        magaciniraj:function(){
            var ispis='<div class="form-inline">';

            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/faktura/magaciniraj',{_token:fakturisanje.token},function(data){

                var rezultat=JSON.parse(data),select;
                fakturisanje.magaciniranje.pozicije=[];
                for(var i=0; i<fakturisanje.magaciniranje.proizvodi.length; i++){
                    fakturisanje.magaciniranje.pozicije[i]=[];

                    ispis+= '<div class="col-sm-3">'+fakturisanje.magaciniranje.proizvodi[i]['sifra']+'</div>' +
                            '<div class="col-sm-8">'+fakturisanje.magaciniranje.proizvodi[i]['naziv']+' <i data-toggle="tooltip" title="'+fakturisanje.tekst.ukupno+' '+(fakturisanje.vrstaFakture==1?fakturisanje.tekst.naruceno:fakturisanje.tekst.pristiglo)+'">('+fakturisanje.magaciniranje.proizvodi[i]['kolicina']+')</i></div>' +
                            '<div class="col-sm-1" id="proizvid-stanje-'+i+'" data-toggle="tooltip" title="'+fakturisanje.tekst.ukupnoMagacinirano+'">0</div>';
                    for(var j=0; j<rezultat.magacini[i].length; j++) {
                        fakturisanje.magaciniranje.pozicije[i][j]={niz:[], broj_pozicija:0};
                        ispis+= '<div class="col-sm-offset-1 col-sm-4">' + rezultat.magacini[i][j]['naziv'] + '</div>' +
                                '<div class="col-sm-6" style="text-align: right"><span style="font-style: italic;font-size:80%;font-weight: bold" data-toggle="tooltip" title="Na stanju u magacinu">&sum; '+(rezultat.magacini[i][j]['ukupno_u_magacinu']?rezultat.magacini[i][j]['ukupno_u_magacinu']:0)+'</span></div>';

                        ispis+='<div id="_pozicije-'+i+'-'+j+'">';
                        for(var k = 0; k<rezultat.pozicije[i][j].length; k++){
                            select='';
                            for (var kk = 0; kk <= (fakturisanje.vrstaFakture==1?
                                Math.min(rezultat.magacini[i][j]['ukupno_u_magacinu'],fakturisanje.magaciniranje.proizvodi[i]['kolicina'],rezultat.pozicije[i][j][k]['kolicina'])
                                :fakturisanje.magaciniranje.proizvodi[i]['kolicina']); kk++) {
                                select += '<option value="' + kk + '">' + kk + '</option>';
                            }
                            ispis+= '<div class="_pozicija">' +
                                        '<div class="col-sm-offset-2 col-sm-3">'+fakturisanje.tekst.pozicija+'</div>' +
                                        '<div class="col-sm-7">'+
                                            '<div class="form-group" style="margin-right: 2px">' +
                                                '<label class="sr-only" for="exampleInputAmount">'+fakturisanje.tekst.stolaza+'</label>' +
                                                '<div class="input-group">' +
                                                    '<div class="input-group-addon"><i class="glyphicon glyphicon-resize-horizontal" style="font-size: 70%"></i></div>' +
                                                    '<input id="stolaza-'+i+'-'+j+'-'+k+'" class="form-control" style="width:40px" value="'+rezultat.pozicije[i][j][k]['stolaza']+'" disabled>' +
                                                '</div>' +
                                            '</div>' +

                                            '<div class="form-group" style="margin-right: 2px">' +
                                                '<label class="sr-only">'+fakturisanje.tekst.polica+'</label>' +
                                                '<div class="input-group">' +
                                                    '<div class="input-group-addon"><i class="glyphicon glyphicon-resize-vertical" style="font-size: 70%"></i></div>' +
                                                    '<input id="polica-'+i+'-'+j+'-'+k+'" class="form-control" style="width:40px" value="'+rezultat.pozicije[i][j][k]['polica']+'" disabled>' +
                                                '</div>' +
                                            '</div>' +

                                            '<div class="form-group" style="margin-right: 2px">' +
                                                '<label class="sr-only">'+fakturisanje.tekst.pozicija+'</label>' +
                                                '<div class="input-group">' +
                                                    '<div class="input-group-addon"><i class="glyphicon glyphicon-indent-left" style="font-size: 70%"></i></div>' +
                                                    '<input id="pozicija-'+i+'-'+j+'-'+k+'" class="form-control" style="width:40px" value="'+rezultat.pozicije[i][j][k]['pozicija']+'" disabled>' +
                                                '</div>' +
                                            '</div>' +

                                            '<div class="form-group" style="margin-right: 2px">' +
                                                '<label class="sr-only">'+fakturisanje.tekst.kolicina+'</label>' +
                                                '<div class="input-group">' +
                                                    '<div class="input-group-addon"><i class="glyphicon glyphicon-stats" style="font-size: 70%"></i></div>' +
                                                    '<select onchange="fakturisanje.funkcije.unosTest()" id="kolicina-'+i+'-'+j+'-'+k+'" class="form-control">'+select+'</select>' +
                                                '</div>' +
                                            '</div>' +
                                                '<button class="btn btn-xs btn-danger" style="width:40px" onclick="fakturisanje.funkcije.ukloniPoziciju(this,'+i+','+j+','+k+')"><i class="glyphicon glyphicon-trash"></i></button>' +
                                            '</div>' +
                                    '</div>';

                            fakturisanje.magaciniranje.pozicije[i][j].niz[k]=rezultat.pozicije[i][j][k];
                            fakturisanje.magaciniranje.pozicije[i][j].niz[k].div_id=k;
                            fakturisanje.magaciniranje.pozicije[i][j].broj_pozicija=k+1;
                        }
                        ispis+= '</div>' +
                        ([4,5].indexOf(fakturisanje.vrstaFakture)>-1?'<div class="col-sm-offset-5 col-sm-7">' +// && rezultat.magacini[i][j]['ukupno_u_magacinu']
                                    '<button class="btn btn-xs btn-primary" data-toggle="tooltip" title="Dodaj poziciju" onclick="fakturisanje.funkcije.dodajPoziciju('+i+','+j+','+fakturisanje.magaciniranje.proizvodi[i]['kolicina']+')"><i class="glyphicon glyphicon-plus"></i></button>' +
                                '</div>':'');
                    }
                    ispis+='<br clear="all"><hr>';
                }
                ispis+='</div><button class="btn btn-primary" onclick="fakturisanje.funkcije.zavrsiMagaciniranje()">'+fakturisanje.tekst.magaciniraj+'</button>';
                $('#work-place').hide();
                $('#work-place').html(ispis);
                $('#work-place').fadeIn();
                $('[data-toggle=tooltip]').tooltip();
                fakturisanje.magaciniranje.magacini=rezultat.magacini;fakturisanje.funkcije.defaultMagaciniranje();
            })
        },
        zavrsiMagaciniranje:function(){
            fakturisanje.dorada=null;
            if(fakturisanje.funkcije.unosTest()){
                var pozicije=[];
                for(var proizvod=0; proizvod<fakturisanje.magaciniranje.proizvodi.length; proizvod++) {
                    for (var magacin = 0; magacin < fakturisanje.magaciniranje.magacini[proizvod].length; magacin++) {
                        $.each(fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz, function (pozicija, value) {
                            if(value)
                                if(value.kolicina){
                                    if(!pozicije[proizvod]) pozicije[proizvod]=[];
                                    if(value.pozicija_u_magacinu_id) pozicije[proizvod].push({
                                        pozicija_u_magacinu_id:value.pozicija_u_magacinu_id,
                                        kolicina:value.kolicina,
                                        magacin_id_id:fakturisanje.magaciniranje.magacini[proizvod][magacin]['id'],
                                        magacin_id:fakturisanje.magaciniranje.magacini[proizvod][magacin]['magacin_id']
                                    })
                                    else {
                                        value.magacin_id_id=fakturisanje.magaciniranje.magacini[proizvod][magacin]['id'];
                                        value.magacin_id=fakturisanje.magaciniranje.magacini[proizvod][magacin]['magacin_id'];
                                        delete value.div_id;
                                        pozicije[proizvod].push(value);
                                    }
                                }
                        });
                    }
                }
                $.post('/administracija/faktura/zavrsi-magaciniranje',{
                    _token:fakturisanje.token,
                    pozicije:pozicije
                },function(data){
                    var rezultat=JSON.parse(data);
                    $('#work-place').hide();
                    if(rezultat.dorada){
                        fakturisanje.dorada=rezultat.dorada;
                        var ispis='<div class="form-horizontal">';
                        for(var i=0;i<fakturisanje.dorada.length;i++){
                            ispis+='<div class="col-sm-12">'+rezultat.dorada[i].naziv+' '+rezultat.dorada[i].sifra+' '+fakturisanje.tekst.magacin+':'+fakturisanje.funkcije.pronadjiImeMagacina(parseInt(rezultat.dorada[i].midid))+'</div><div class="form-group"><label class="control-label col-sm-7">'+fakturisanje.tekst.minKolicinaUMag+'</label><div class="col-sm-2"><input id="kolicina-'+i+'" class="form-control"></div><div class="col-sm-3"></div></div><div class="form-group"><label class="control-label col-sm-7">'+fakturisanje.tekst.cijena+'</label><div class="col-sm-2"><input id="cijena-'+i+'" class="form-control"></div><div class="col-sm-3"></div></div>';
                        }
                        $('#work-place').html('<div class="alert alert-danger">'+fakturisanje.tekst.proizvodiNisuUMag+'</div>'+ispis+'</div><button class="btn btn-primary" onclick="fakturisanje.funkcije.zavrsiDoradu()"><i class="glyphicon glyphicon-floppy-disk"></i> '+fakturisanje.tekst.sacuvaj+'</button>');
                    }
                    else $('#work-place').html('<div class="alert alert-success">'+fakturisanje.tekst.akcijaJeUspjesna+' <a href="'+rezultat.link+'" target="_blank" class="btn btn-primary">PDF</div></div>');
                    $('#work-place').fadeIn();
                })
            }
        },
        zavrsiDoradu:function(){
            var dorada=[];
            for(var i=0;i<fakturisanje.dorada.length;i++)
                dorada.push({kolicina:parseFloat($('#kolicina-'+i).val()),cijena:parseFloat($('#cijena-'+i).val()),midid:fakturisanje.dorada[i].midid});
            $.post('/administracija/faktura/zavrsi-magaciniranje',{_token:fakturisanje.token,dorada:JSON.stringify(dorada)},function(data){
                data=JSON.parse(data);
                $('#work-place').html('<div class="alert alert-success">'+fakturisanje.tekst.akcijaJeUspjesna+' <a href="'+data.link+'" target="_blank" class="btn btn-primary">PDF</div></div>');
            })
        },
        pronadjiImeMagacina:function(id){
            for(var i1=0;i1<fakturisanje.magaciniranje.magacini.length;i1++)
                for(var i2=0;i2<fakturisanje.magaciniranje.magacini[i1].length;i2++)
                    if(id==fakturisanje.magaciniranje.magacini[i1][i2].id) return fakturisanje.magaciniranje.magacini[i1][i2].naziv;
            return '';
        },
        unosTest:function(){
            var globalTest=true, localTest, zbir;
            for(var proizvod=0; proizvod<fakturisanje.magaciniranje.proizvodi.length; proizvod++) {
                localTest = true;
                zbir = 0;
                for (var magacin = 0; magacin < fakturisanje.magaciniranje.magacini[proizvod].length; magacin++) {
                    $.each(fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz, function (index, pozicija) {
                        if(pozicija) {
                            fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz[index].stolaza = parseInt($('#stolaza-' + proizvod + '-' + magacin + '-' + pozicija.div_id).val());
                            fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz[index].polica = parseInt($('#polica-' + proizvod + '-' + magacin + '-' + pozicija.div_id).val());
                            fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz[index].pozicija = parseInt($('#pozicija-' + proizvod + '-' + magacin + '-' + pozicija.div_id).val());
                            fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz[index].kolicina = parseFloat($('#kolicina-' + proizvod + '-' + magacin + '-' + pozicija.div_id).val());
                            zbir += parseFloat(fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz[index].kolicina);
                        }
                    });
                }
                if (zbir != fakturisanje.magaciniranje.proizvodi[proizvod].kolicina){ globalTest=false; localTest=false; }
                for (var magacin = 0; magacin < fakturisanje.magaciniranje.magacini[proizvod].length; magacin++) {
                    $.each(fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz, function (index, pozicija) {
                        if(pozicija) {
                            $('#stolaza-' + proizvod + '-' + magacin + '-' + pozicija.div_id).closest('div.form-group').removeClass('has-success has-error').addClass(localTest?'has-success':'has-error');
                            $('#polica-' + proizvod + '-' + magacin + '-' + pozicija.div_id).closest('div.form-group').removeClass('has-success has-error').addClass(localTest?'has-success':'has-error');
                            $('#pozicija-' + proizvod + '-' + magacin + '-' + pozicija.div_id).closest('div.form-group').removeClass('has-success has-error').addClass(localTest?'has-success':'has-error');
                            $('#kolicina-' + proizvod + '-' + magacin + '-' + pozicija.div_id).closest('div.form-group').removeClass('has-success has-error').addClass(localTest?'has-success':'has-error');
                        }
                    });
                }
                $('#proizvid-stanje-'+proizvod).html(zbir);
            }
            return globalTest;
        },
        defaultMagaciniranje:function(){
            var kolicina, p;
            for(var proizvod=0; proizvod<fakturisanje.magaciniranje.proizvodi.length; proizvod++){
                kolicina=fakturisanje.magaciniranje.proizvodi[proizvod].kolicina;
                for(var magacin=0; magacin<fakturisanje.magaciniranje.magacini[proizvod].length; magacin++){
                    $.each(fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz,function(i,pozicija){
                        if(pozicija) {
                            p=kolicina-$('#kolicina-' + proizvod + '-' + magacin + '-' + pozicija.div_id + ' option:last').val()>=0?$('#kolicina-' + proizvod + '-' + magacin + '-' + pozicija.div_id + ' option:last').val():kolicina;
                            kolicina -= p;
                            $('#kolicina-' + proizvod + '-' + magacin + '-' + pozicija.div_id + ' option').prop('selected', false).filter('[value="'+p+'"]').prop('selected', true);
                        }
                    })
                }
            }
            fakturisanje.funkcije.unosTest();
        },
        dodajPoziciju:function(proizvod,magacin,min){
            var select='';
            for (var k = 0; k <= min; k++)
                select += '<option value="' + k + '">' + k + '</option>';
            $('#_pozicije-'+proizvod+'-'+magacin).append(
            '<div class="_pozicija">' +
                '<div class="col-sm-offset-2 col-sm-3">'+fakturisanje.tekst.pozicija+'</div>' +
                '<div class="col-sm-7">'+
                    '<div class="form-group" style="margin-right: 2px">' +
                        '<label class="sr-only" for="exampleInputAmount">'+fakturisanje.tekst.stolaza+'</label>' +
                        '<div class="input-group">' +
                            '<div class="input-group-addon"><i class="glyphicon glyphicon-resize-horizontal" style="font-size: 70%"></i></div>' +
                            '<input id="stolaza-'+proizvod+'-'+magacin+'-'+fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija+'" class="form-control" style="width:40px" value="0">' +
                        '</div>' +
                    '</div>' +

                    '<div class="form-group" style="margin-right: 2px">' +
                        '<label class="sr-only">'+fakturisanje.tekst.polica+'</label>' +
                        '<div class="input-group">' +
                            '<div class="input-group-addon"><i class="glyphicon glyphicon-resize-vertical" style="font-size: 70%"></i></div>' +
                            '<input id="polica-'+proizvod+'-'+magacin+'-'+fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija+'" class="form-control" style="width:40px" value="0">' +
                        '</div>' +
                    '</div>' +

                    '<div class="form-group" style="margin-right: 2px">' +
                        '<label class="sr-only">'+fakturisanje.tekst.pozicija+'</label>' +
                        '<div class="input-group">' +
                            '<div class="input-group-addon"><i class="glyphicon glyphicon-indent-left" style="font-size: 70%"></i></div>' +
                            '<input id="pozicija-'+proizvod+'-'+magacin+'-'+fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija+'" class="form-control" style="width:40px" value="0">' +
                        '</div>' +
                    '</div>' +

                    '<div class="form-group" style="margin-right: 2px">' +
                        '<label class="sr-only">'+fakturisanje.tekst.kolicina+'</label>' +
                        '<div class="input-group">' +
                            '<div class="input-group-addon"><i class="glyphicon glyphicon-stats" style="font-size: 70%"></i></div>' +
                            '<select onchange="fakturisanje.funkcije.unosTest()" id="kolicina-'+proizvod+'-'+magacin+'-'+fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija+'" class="form-control">'+select+'</select>' +
                        '</div>' +
                    '</div>' +
                    '<button class="btn btn-xs btn-danger" style="width:40px" onclick="fakturisanje.funkcije.ukloniPoziciju(this,'+proizvod+','+magacin+','+fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija+')"><i class="glyphicon glyphicon-trash"></i></button>' +
                '</div>' +
            '</div>'
            );
            fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz[fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija]={
                stolaza:0,
                polica:0,
                pozicija:0,
                kolicina:0,
                div_id:fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija
            };
            fakturisanje.magaciniranje.pozicije[proizvod][magacin].broj_pozicija++;
        },
        ukloniPoziciju:function(element,proizvod,magacin,pozicija){
            var index;
            $.each(fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz, function(i,v){
                if(v)
                    if(v.div_id==pozicija){
                        index=i;
                        return true;
                    }
            });
            fakturisanje.magaciniranje.pozicije[proizvod][magacin].niz.splice(index,1);
            $(element).closest('div._pozicija').remove();
        },
        zavrsiFakturisanje:function(){
            $('#work-place').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/faktura/zavrsi-fakturisanje',{_token:fakturisanje.token},function(data){
                var rezultat=JSON.parse(data);
                $('#work-place').hide();
                $('#work-place').html('<div class="alert alert-success">'+fakturisanje.tekst.akcijaJeUspjesna+' <a href="'+rezultat.link+'" target="_blank" class="btn btn-primary">PDF</div></div>');
                $('#work-place').fadeIn();
            })
        },
        promijeniVrstuKorisnika:function(){
            fakturisanje.vrstaKorisnika=fakturisanje.vrstaKorisnika==3?2:3;
            if([1,2].indexOf(fakturisanje.vrstaFakture)>-1) fakturisanje.vrstaFakture=fakturisanje.vrstaKorisnika==3?2:1;
            $('#vrsta_korisnika').html(fakturisanje.vrstaKorisnika==2?fakturisanje.tekst.kupac:fakturisanje.tekst.dobavljac);
        },
        noviBrojFakture:function(){
            $.post('/administracija/faktura/kreiraj-broj-fakture',{
                _token:fakturisanje.token,
                datum:(new Date($('#datum').val())).getFullYear()
            },function(data){ $('[name=broj_fakture]').val(data+'/'+(new Date($('#datum').val())).getFullYear()); $('[name=broj_fakture]').data('broj_fakture',data) })
        },
        nadjiKorisnika:function(){
            $('#pretraga_div').html('<center><i class="icon-spin6 animate-spin" style="font-size:350%"></i></center>');
            $.post('/administracija/faktura/ucitaj-korisnike',{
                _token:fakturisanje.token,
                vrsta_korisnika:fakturisanje.vrstaKorisnika,
                pretraga:$('[name=pretrazi_korisnika]').val()
            },function(data){
                var ispis='', rezultat=JSON.parse(data);
                if(rezultat.length){
                    ispis+='<div class="list-group">';
                    $.each(rezultat, function(index, value) {
                        ispis += '' +
                        '<a href="#" class="list-group-item" onclick="fakturisanje.funkcije.izaberiKorisnika(' + value['id'] + ')">' +
                        (value['prezime']?'<b class="list-group-item-heading">' + value['prezime'] + ' ' + value['ime'] + '</b> ':'') +
                        (value['jmbg']?'<b class="list-group-item-heading">' + value['jmbg'] + '</b> ':'') +
                        (value['naziv']?'<b class="list-group-item-heading">' + value['naziv'] + '</b> ':'') +
                        (value['adresa']?'<p class="list-group-item-text" style="text-align: left">' + value['adresa'] + ' ' + value['grad'] + '</p> ':'') +
                        (value['telefon']?'<p class="list-group-item-text" style="text-align: left">' + value['telefon'] + '</p> ':'') +
                        '</a>';
                    });
                    ispis+='</div>';
                }else ispis+='<div class="alert alert-danger">'+fakturisanje.tekst.nemaRezultataPretrage+' '+fakturisanje.tekst.izvrsiteDodavanjeKorisnika+'.</div>';
                $('#pretraga_div').html(ispis);
            })
        },
        izaberiKorisnika:function(idKorisnika){
            $('#promijeniVrstuKorisnika').hide();
            $('#dobavljac_div').html('<center><i class="icon-spin6 animate-spin" style="font-size: 350%"></i></center>');
            $.post('/administracija/faktura/izaberi-korisnika',{
                _token:fakturisanje.token,
                id:idKorisnika
            },function(data){
                var rezultat=JSON.parse(data);
                $('#dobavljac_div').hide();
                $('#dobavljac_div').html('' +
                '<table class="">' +
                (rezultat.prezime?'<tr><td>'+fakturisanje.tekst.ime+' '+fakturisanje.tekst.i+' '+fakturisanje.tekst.prezime+'</td><td><b>' + rezultat.ime + ' ' + rezultat.prezime + '</b></td></tr>':'') +
                (rezultat.jmbg?'<tr><td>'+fakturisanje.tekst.jmbg+'</td><td><b>' + rezultat.jmbg + '</b></td></tr>':'') +
                (rezultat.broj_licne_karte?'<tr><td>'+fakturisanje.tekst.brLicneKarte+'</td><td><b>' + rezultat.broj_licne_karte + '</b></td></tr>':'') +
                (rezultat.naziv?'<tr><td>'+fakturisanje.tekst.naziv+'</td><td><b>' + rezultat.naziv + '</b></td></tr>':'') +
                (rezultat.adresa?'<tr><td>'+fakturisanje.tekst.adresa+'</td><td><b>' + rezultat.adresa + '</b></td></tr>':'') +
                (rezultat.grad?'<tr><td></td><td><b>' + rezultat.grad + '</b></td></tr>':'') +
                (rezultat.jib?'<tr><td>'+fakturisanje.tekst.jib+'</td><td><b>' + rezultat.jib + '</b></td></tr>':'') +
                (rezultat.pib?'<tr><td>'+fakturisanje.tekst.pib+'</td><td><b>' + rezultat.pib + '</b></td></tr>':'') +
                (rezultat.pdv?'<tr><td>'+fakturisanje.tekst.pdv+'</td><td><b>' + rezultat.pdv + '</b></td></tr>':'') +
                (rezultat.banka_1?'<tr><td>'+fakturisanje.tekst.banka+'</td><td><b>' + rezultat.banka_1 + '</b></td></tr>':'') +
                (rezultat.ziro_racun_1?'<tr><td>'+fakturisanje.tekst.ziroRacun+'</td><td><b>' + rezultat.ziro_racun_1 + '</b></td></tr>':'') +
                (rezultat.banka_2?'<tr><td>'+fakturisanje.tekst.banka+'</td><td><b>' + rezultat.banka_2 + '</b></td></tr>':'') +
                (rezultat.ziro_racun_2?'<tr><td>'+fakturisanje.tekst.ziroRacun+'</td><td><b>' + rezultat.ziro_racun_2 + '</b></td></tr>':'') +
                (rezultat.registracija?'<tr><td>'+fakturisanje.tekst.registracija+'</td><td><b>' + rezultat.registracija + '</b></td></tr>':'') +
                (rezultat.broj_upisa?'<tr><td>'+fakturisanje.tekst.brUpisa+'</td><td><b>' + rezultat.broj_upisa + '</b></td></tr>':'') +
                '</table>');
                $('#dobavljac_div').fadeIn();
                fakturisanje.funkcije.ucitajTabeluProizvoda();
            })
        }
    },
    tekst:{
        mojiPodaci:'Moji podaci',
        naziv:'Naziv',
        adresa:'Adresa',
        jib:'JIB',
        pib:'PIB',
        pdv:'PDV',
        banka:'Banka',
        ziroRacun:'Žiro račun',
        registracija:'Registracija',
        brUpisa:'Broj upisa',
        kupac:'Kupac',
        dobavljac:'Dobavljač',
        prezime:'Prezime',
        i:'i',
        ime:'Ime',
        jmbg:'JMBG',
        brLicneKarte:'Broj lične karte',
        datum:'Datum',
        brFakture:'Broj fakture',
        naOsnovu:'Na osnovu',
        placanje:'Plaćanje',
        napomena:'Napomena',
        napomenaPlaceholder:'Unesite napomenu koja će da bude upisana u dokument.',
        pripremiZaIzvrsenja:'Pripremi za izvršenje',
        izvrsi:'Izvrši',
        otkazi:'Otkaži',
        redniBr:'Redni broj',
        sifraProizvoda:'Šifra proizvoda',
        nazivArtikla:'Naziv artikla',
        kolicina:'Količina',
        jedinicaMjere:'Jedinica mjere',
        cijena:'Cijena',
        maloprodajnaCijena:'Maloprodajna cijena',
        iznosBezPdv:'Iznos bez PDV-a',
        pdv:'PDV',
        iznosSaPdv:'Iznos sa PDV-om',
        ukupnoBezPdv:'Ukupno bez PDV-a',
        iznosPdv:'Iznos PDV-a',
        ukupnoSaPdv:'Ukupno sa PDV-om',
        ukupnoZaUplatu:'Ukupno za uplatu',
        akcijaJeSpremna:'Akcija je spremna za izvršenje.',
        akcijomCeBitiAzurirano:'Sa ovom akcijom će biti ažurirano stanje proizvoda u magacinu.',
        provjeritePodatkeIzvrsi:'Provjerite podatke i pristupite izvršenju.',
        ukupno:'Ukupno',
        naruceno:'Naručeno',
        pristiglo:'Pristiglo',
        ukupnoMagacinirano:'Ukupno magacinirano',
        pozicija:'Pozicija',
        stolaza:'Stolaža',
        polica:'Polica',
        magaciniraj:'Magaciniraj',
        akcijaJeUspjesna:'Akcija je uspješno izvršena.',
        nemaRezultataPretrage:'Nema rezultata za navedenu pretragu.',
        izvrsiteDodavanjeKorisnika:'Izvršite dodavanje korisnika u evidenciju.',
        pripremiZaIzvrsenje:'Pripremi za izvršenje',
        proizvodiNisuUMag:'Sledeći proizvodi se ne nalaze u ciljanom magacinu. Unesite potrebne podatke i sačuvajte promjene.',
        minKolicinaUMag:'Minimalna količina u magacinu',
        sacuvaj:'Sačuvaj',
        magacin:'Magacin'
    },
    setTekst:function(index,tekst){
        fakturisanje.tekst[index]=tekst;
    }
}