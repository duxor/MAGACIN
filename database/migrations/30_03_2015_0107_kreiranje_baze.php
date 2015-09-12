<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;
class KreiranjeBaze extends Migration {

	public function up()
	{
		Schema::create('prava_pristupa', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('naziv', 45)->unique();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('vrsta_korisnika', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('naziv', 45)->unique();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('korisnici', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('prezime', 45)->nullable();
			$table->string('ime', 45)->nullable();
			$table->string('username', 45)->unique();
			$table->string('password', 150);
			$table->string('email', 45)->unique();
			$table->string('token', 250)->nullable();
			$table->unsignedBigInteger('prava_pristupa_id');
			$table->foreign('prava_pristupa_id')->references('id')->on('prava_pristupa');
			$table->unsignedBigInteger('vrsta_korisnika_id');
			$table->foreign('vrsta_korisnika_id')->references('id')->on('vrsta_korisnika');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->string('naziv',250)->nullable();
			$table->string('adresa',250)->nullable();
			$table->string('grad',250)->nullable();
			$table->string('jib',250)->nullable();
			$table->string('pib',250)->nullable();
			$table->string('pdv',250)->nullable();
			$table->string('ziro_racun_1',250)->nullable();
			$table->string('banka_1',250)->nullable();
			$table->string('ziro_racun_2',250)->nullable();
			$table->string('banka_2',250)->nullable();
			$table->string('registracija',250)->nullable();
			$table->string('broj_upisa',250)->nullable();
			$table->string('telefon',45)->nullable();
			$table->text('opis');
		});
		Schema::create('log', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->unsignedBigInteger('korisnici_id');
			$table->foreign('korisnici_id')->references('id')->on('korisnici');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
		Schema::create('aplikacija',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('naziv', 45);
			$table->string('slug', 45);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('korisnici_id');
			$table->foreign('korisnici_id')->references('id')->on('korisnici');
			$table->text('opis')->nullable();
			$table->text('napomena')->nullable();
			$table->string('logo', 250)->nullable();
			$table->tinyInteger('aktivan')->default(1);					
		});
		Schema::create('vrsta_proizvoda',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('naziv', 45);
			$table->text('napomena')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('aplikacija_id');
			$table->foreign('aplikacija_id')->references('id')->on('aplikacija');
		});
		Schema::create('proizvod',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('sifra', 45);
			$table->string('naziv', 45);
			$table->text('opis')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->string('bar_kod',200)->nullable();
			$table->string('proizvodjac',250)->nullable();
			$table->string('jedinica_mjere',45)->nullable();
			$table->unsignedBigInteger('pakovanje_kolicina')->nullable();
			$table->string('pakovanje_jedinica_mjere',45)->nullable();
			$table->unsignedBigInteger('vrsta_proizvoda_id');
			$table->foreign('vrsta_proizvoda_id')->references('id')->on('vrsta_proizvoda');
			$table->unsignedBigInteger('aplikacija_id');
			$table->foreign('aplikacija_id')->references('id')->on('aplikacija');
			$table->string('foto', 250)->nullable();
		});
		Schema::create('pozicija',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->integer('stolaza');
			$table->integer('polica');
			$table->integer('pozicija');
			$table->text('opis')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('aplikacija_id');
			$table->foreign('aplikacija_id')->references('id')->on('aplikacija');
		});

		Schema::create('magacin_id',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('naziv', 45);
			$table->text('opis')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('aplikacija_id');
			$table->foreign('aplikacija_id')->references('id')->on('aplikacija');
		});
		
		Schema::create('magacin',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('magacin_id_id');
			$table->foreign('magacin_id_id')->references('id')->on('magacin_id');
			$table->unsignedBigInteger('proizvod_id');
			$table->foreign('proizvod_id')->references('id')->on('proizvod');
			$table->integer('kolicina_stanje');
			$table->integer('kolicina_min');
			$table->unsignedBigInteger('pozicija_id');
			$table->foreign('pozicija_id')->references('id')->on('pozicija');
			$table->tinyInteger('naruceno')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->float('cijena')->nullable();
			$table->float('rabat')->nullable()->default(0);
			$table->float('cijena_rabat')->nullable();
		});
		Schema::create('vrsta_fakture',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('naziv',45);
			$table->text('napomena')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();

		});
		Schema::create('fakture',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->date('datum_narudzbe');
			$table->date('datum_isporuke')->nullable();
			$table->tinyInteger('potvrda')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('vrsta_fakture_id');
			$table->foreign('vrsta_fakture_id')->references('id')->on('vrsta_fakture');
		});
		Schema::create('za_narudzbu',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->tinyInteger('aktivan')->default(1);
			$table->integer('kolicina_porucena');
			$table->integer('kolicina_pristigla')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('narudzbenice_id');
			$table->unsignedBigInteger('proizvod_id')->nullable();
			$table->foreign('narudzbenice_id')->references('id')->on('fakture');
			$table->foreign('proizvod_id')->references('id')->on('proizvod');	
		});
		Schema::create('korisnici_aplikacije',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('napomena', 45);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('korisnici_id');
			$table->foreign('korisnici_id')->references('id')->on('korisnici');
			$table->unsignedBigInteger('aplikacija_id');
			$table->foreign('aplikacija_id')->references('id')->on('aplikacija');		
		});
		Schema::create('proizvod_iz_magacina',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->text('napomena')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
			$table->unsignedBigInteger('za_narudzbu_id');
			$table->foreign('za_narudzbu_id')->references('id')->on('za_narudzbu');
			$table->unsignedBigInteger('magacin_id');
			$table->foreign('magacin_id')->references('id')->on('magacin');
		});
	}
	public function down()
	{
		Schema::drop('proizvod_iz_magacina');
		Schema::drop('za_narudzbu');
		Schema::drop('fakture');
		Schema::drop('vrsta_fakture');
		Schema::drop('magacin');
		Schema::drop('magacin_id');
		Schema::drop('pozicija');
		Schema::drop('proizvod');
		Schema::drop('vrsta_proizvoda');
		Schema::drop('korisnici_aplikacije');
		Schema::drop('aplikacija');
		Schema::drop('log');
		Schema::drop('korisnici');
		Schema::drop('vrsta_korisnika');	
		Schema::drop('prava_pristupa');
	}

}
