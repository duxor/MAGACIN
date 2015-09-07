@extends('administracija.master')

@section('content')
    <h2 style="text-align: left" id="korisnici"><i class="glyphicon glyphicon-user"></i> Korisnici
        <button class="btn btn-primary" data-toggle="modal" data-target="#noviKorisnik"><i class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Dodaj novog korisnika"></i></button>
        <div class="form-inline" style="float: right">
            <button class="btn btn-sm btn-default" data-toggle="tooltip" title="Pronađi korisnika"><i class="glyphicon glyphicon-search"></i></button>
            <div class="form-group">{!!Form::text('pretraga_primna',null,['class'=>'form-control'])!!}</div>
            <div class="form-group">{!!Form::select('pretraga_vrsta_korisnika',array_merge([0=>'Svi korisnici'],$vrstaKorisnika),0,['class'=>'form-control'])!!}</div>
        </div>
    </h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Prezime i ime</th>
                <th>Vrsta korisnika</th>
                <th>Prava pristupa</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($korisnici as $korisnik)
                <tr>
                    <td>{{$korisnik['prezime']}} {{$korisnik['ime']}}</td>
                    <td>@if($korisnik['vrsta_korisnika_id']<4)
                            {!!Form::select('vrsta_korisnika_'.$korisnik['id'],$vrstaKorisnika,$korisnik['vrsta_korisnika_id'],['class'=>'form-control'])!!}
                        @else
                            {{$korisnik['vrsta_korisnika_naziv']}}
                        @endif
                    </td>
                    <td>@if($korisnik['prava_pristupa_id']<4)
                            {!!Form::select('prava_pristupa_'.$korisnik['id'],$pravaPristupa,$korisnik['prava_pristupa_id'],['class'=>'form-control'])!!}
                        @else
                            {{$korisnik['prava_pristupa_naziv']}}
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary" data-toggle="tooltip" title="Sačuvaj izmjene" onclick="azuriraj({{$korisnik['id']}})"><i class="glyphicon glyphicon-ok"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        $(function(){$('[data-toggle="tooltip"]').tooltip()})
        function azuriraj(id){
            console.log($('[name=vrsta_korisnika_'+id+']').val())
        }
        function toTop(){$('#noviKorisnik').animate({scrollTop:0}, 'slow')}
        function dodajNovogKorisnika(){
            if(SubmitForm.check('hide'))
                Komunikacija.posalji("/administracija/korisnici","hide","poruka","wait","hide");
        }
    </script>
    <div class="modal fade" id="noviKorisnik">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h1><i class="glyphicon glyphicon-user"></i> Novi korisnik</h1>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div id="poruka" style="display: none"></div><i class='icon-spin6 animate-spin' style="color: rgba(0,0,0,0)"></i>
                        <div id="wait" style="display:none"><center><i class='icon-spin6 animate-spin' style="font-size: 350%"></i></center></div>
                        <div id="hide">
                            {!!Form::hidden('_token',csrf_token())!!}
                            <div id="dprezime" class="form-group has-feedback">
                                <label class="col-sm-4">Prezime</label>
                                <div class="col-sm-8">
                                    {!!Form::text('prezime',null,['class'=>'form-control','id'=>'prezime'])!!}
                                    <span id="sprezime" class="glyphicon form-control-feedback"></span>
                                </div>
                            </div>
                            <div id="dime" class="form-group has-feedback">
                                <label class="col-sm-4">Ime</label>
                                <div class="col-sm-8">
                                    {!!Form::text('ime',null,['class'=>'form-control','id'=>'ime'])!!}
                                    <span id="sime" class="glyphicon form-control-feedback"></span>
                                </div>

                            </div>
                            <div id="dusername" class="form-group has-feedback">
                                <label class="col-sm-4">Username</label>
                                <div class="col-sm-8">
                                    {!!Form::text('username',null,['class'=>'form-control','id'=>'username'])!!}
                                    <span id="susername" class="glyphicon form-control-feedback"></span>
                                </div>
                            </div>
                            <div id="dpassword" class="form-group has-feedback">
                                <label class="col-sm-4">Password</label>
                                <div class="col-sm-8">
                                    {!!Form::password('password',['class'=>'form-control','id'=>'password'])!!}
                                    <span id="susername" class="glyphicon form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Email</label>
                                <div class="col-sm-8">{!!Form::email('email',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Prava pristupa</label>
                                <div class="col-sm-8">{!!Form::select('prava_pristupa_id',$pravaPristupa,1,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Vrsta korisnika</label>
                                <div class="col-sm-8">{!!Form::select('vrsta_korisnika_id',$vrstaKorisnika,1,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Naziv</label>
                                <div class="col-sm-8">{!!Form::text('nziv',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Adresa</label>
                                <div class="col-sm-8">{!!Form::text('adresa',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Grad</label>
                                <div class="col-sm-8">{!!Form::text('grad',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">JIB</label>
                                <div class="col-sm-8">{!!Form::text('jib',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">PIB</label>
                                <div class="col-sm-8">{!!Form::text('pib',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">PDV</label>
                                <div class="col-sm-8">{!!Form::text('pdv',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Ziro račun 1</label>
                                <div class="col-sm-8">{!!Form::text('ziro_racun_1',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Banka 1</label>
                                <div class="col-sm-8">{!!Form::text('banka_1',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Ziro račun 2</label>
                                <div class="col-sm-8">{!!Form::text('ziro_racun_2',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Banka 1</label>
                                <div class="col-sm-8">{!!Form::text('banka_2',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Registracija</label>
                                <div class="col-sm-8">{!!Form::text('registracija',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Broj upisa</label>
                                <div class="col-sm-8">{!!Form::text('broj_upisa',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Telefon</label>
                                <div class="col-sm-8">{!!Form::text('telefon',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4">Opis</label>
                                <div class="col-sm-8">{!!Form::textarea('opis',null,['class'=>'form-control'])!!}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4"></label>
                                <div class="col-sm-8">
                                    {!!Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Sačuvaj',['class'=>'btn btn-primary','onclick'=>'dodajNovogKorisnika();toTop()'])!!}
                                    {!!Form::button('<i class="glyphicon glyphicon-trash"></i> Otkaži',['class'=>'btn btn-danger','data-dismiss'=>'modal'])!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection