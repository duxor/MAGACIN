<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('log', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->unsignedBigInteger('korisnici_id');
			$table->foreign('korisnici_id')->references('id')->on('korisnici');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('proizvod',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('sifra', 45);
			$table->string('naziv', 45);
			$table->text('opis')->nullable();
			$table->float('cijena_nabavna')->nullable();
			$table->float('cijena_prodajna')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('magacinid',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->string('naziv', 45);
			$table->text('opis')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('pozicija',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->integer('stolaza');
			$table->integer('polica');
			$table->integer('pozicija');
			$table->text('opis')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('magacin',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('magacinid_id');
			$table->foreign('magacinid_id')->references('id')->on('magacinid');
			$table->unsignedBigInteger('proizvod_id');
			$table->foreign('proizvod_id')->references('id')->on('proizvod');
			$table->integer('kolicina_stanje');
			$table->integer('kolicina_min');
			$table->unsignedBigInteger('pozicija_id');
			$table->foreign('pozicija_id')->references('id')->on('pozicija');
			$table->tinyInteger('naruceno')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('narudzbenice',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->dateTime('datum_narudzbe');
			$table->dateTime('datum_isporuke')->nullable();
			$table->tinyInteger('potvrda')->default(0);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
		Schema::create('za_narudzbu',function(Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('magacin_id')->nullable();
			$table->foreign('magacin_id')->references('id')->on('magacin');
			$table->tinyInteger('aktivan')->default(1);
			$table->integer('kolicina_porucena');
			$table->integer('kolicina_pristigla')->default(0);
			$table->unsignedBigInteger('narudzbenice_id');
			$table->foreign('narudzbenice_id')->references('id')->on('narudzbenice');
			$table->unsignedBigInteger('proizvod_id')->nullable();
			$table->foreign('proizvod_id')->references('id')->on('proizvod');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->nullable();
		});
	}


	public function down()
	{
		Schema::drop('log');
		Schema::drop('korisnici');
		Schema::drop('prava_pristupa');

		Schema::drop('za_narudzbu');
		Schema::drop('narudzbenice');

		Schema::drop('magacin');
		Schema::drop('proizvod');
		Schema::drop('magacinID');
		Schema::drop('pozicija');

	}

}
