@extends('admin-master')

@section('content')
    Magacin v1.0

<style>
#leftmenu {
	width: 235px;
	background-color: #65BC7D;
	border-radius: 10px;
	padding: 20px 20px 0 20px;
	position: absolute;
	left: -100px;
	z-index: 100;
}
#dashboard img {
	margin-bottom: 20px;
	border: 1px solid rgb(0,0,0);
}
</style>

<script src="js/_js/jquery.easing.1.3.js"></script>
<script src="js/_js/jquery.color.js"></script>
<script>
$(document).ready(function() {
  $('#leftmenu').hover(
     function() {
		$(this).stop().animate(
		{
			left: '0',
			backgroundColor: 'rgb(255,255,255)'
		},
		500, 'easeInSine'
		);
	 }, 
	 function() {
		 $(this).stop().animate(
		{
			left: '-100px',
			backgroundColor: '#65BC7D'
		},
		1500,
		'easeOutBounce'
		);
	 }
  );
});
</script>     


<div id="leftmenu">
<ul class="nav navbar-nav navbar-left">
    <li><a href="/administracija/korisnici"><span class="glyphicon glyphicon-user"></span> Korisnici</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span>  Magacini <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="/administracija/magacin"><span class="glyphicon glyphicon-eye-open"></span> Pregled</a></li>
            <li><a href="/administracija/magacin/novi"><span class="glyphicon glyphicon-plus"></span> Dodaj novi</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-lamp"></span> Proizvodi <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="/administracija/proizvod"><span class="glyphicon glyphicon-eye-open"></span> Pregled i ažuriranje</a></li>
            <li><a href="/administracija/proizvod/novi"><span class="glyphicon glyphicon-plus"></span> Dodavanje novog</a></li>
            <li><a href="#search"><span class="glyphicon glyphicon-search"></span> Pretraga</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-shopping-cart"></span>@if(\App\OsnovneMetode::nestanakProizvoda()>0)<span class="badge">{{\App\OsnovneMetode::nestanakProizvoda()}}</span>@endif Za narudžbu <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="/administracija/proizvod/za-narudzbu"><span class="glyphicon glyphicon-eye-open"></span> Aktuelno</a></li>
            <li><a href="/administracija/proizvod/narudzbe"><span class="glyphicon glyphicon-briefcase"></span> Arhiva</a></li>
        </ul>
    </li>
    <li><a href="/administracija/logout"><span class="glyphicon glyphicon-off"></span> Odjava</a></li>
</ul>
</div>



    @stop