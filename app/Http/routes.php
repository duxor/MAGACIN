<?php

Route::controller('/administracija/magacin','Magacin');
Route::controller('/administracija/proizvod','Proizvod');
Route::controller('/administracija/korisnici','KorisniciKontroler');
Route::controller('/administracija','Administracija');
Route::get('/faktura','Glavni@faktura');
Route::controller('/','Glavni');