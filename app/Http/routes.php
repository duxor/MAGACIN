<?php

Route::controller('/administracija/magacin','Magacin');
Route::controller('/administracija/proizvod','Proizvod');
Route::controller('/administracija','Administracija');
Route::controller('/','Glavni');

Route::post('/administracija/search', 'Search');