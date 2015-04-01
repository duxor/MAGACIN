$(document).ready(function() {
	// navigation click actions	
	$('.scroll-link').on('click', function(event){
		event.preventDefault();
		var sectionID = $(this).attr("data-id");
		scrollToID('#' + sectionID, 750);
	});
	// scroll to top action
	$('.scroll-top').on('click', function(event) {
		event.preventDefault();
		$('html, body').animate({scrollTop:0}, 'slow'); 		
	});
	// mobile nav toggle
	$('#nav-toggle').on('click', function (event) {
		event.preventDefault();
		$('#main-nav').toggleClass("open");
	});
});
//Search
$(function () {
    $('a[href="#search"]').on('click', function(event) {
        event.preventDefault();
        $('#search').addClass('open');
        $('#search > form > input[type="search"]').focus();
    });
    
    $('#search, #search button.close').on('click keyup', function(event) {
        if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
            $(this).removeClass('open');
        }
    });
    
    
   
});


// scroll function
function scrollToID(id, speed){
	var offSet = 50;
	var targetOffset = $(id).offset().top - offSet;
	var mainNav = $('#main-nav');
	$('html,body').animate({scrollTop:targetOffset}, speed);
	if (mainNav.hasClass("open")) {
		mainNav.css("height", "1px").removeClass("in").addClass("collapse");
		mainNav.removeClass("open");
	}
}
if (typeof console === "undefined") {
    console = {
        log: function() { }
    };
}
var SubmitForma = new SubmitForm();

/*#
 ### Autor: Dusan Perisci
 ### Home: dusanperisic.com
 ###
 ### Napomena: 	Klasa je pisana kao dodatak Bootstrap framework-a
 ###			Voditi racuna o formatiranju koje je potrebno da bude zadovoljeno (Pogledati primjer ispod)
 ### 			Ukoliko ne želite da se određeno polje nađe u provjeri ne treba mu dodjeljivati ID
 ###			ID = NAZIV_POLJA
 ###			ID_DIV = dID
 ###			ID_SPAN = sID
 ### ------------------------------------------------------------------
 ### Primjer:
 ### JS: var SubmitForma = new SubmitForm();
 ### HTML:
 ### <form id="forma" class="form-horizontal">
 ### 	<div id="dime" class="form-group has-feedback">
 ###		<label for="ime" class="control-label col-sm-2">Ime</label>	
 ###		<div class="col-sm-10">
 ###			<input id="ime" name="ime" class="form-control" placeholder="Unesite vaše ime">
 ### 			<span id="sime" class="glyphicon form-control-feedback"></span>
 ###		</div>
 ### 	</div>
 ###	<div class="form-group">
 ###		<div class="col-sm-2"></div>
 ###		<div class="col-sm-10">
 ###			<button type="button" class="btn btn-lg btn-success" onClick="SubmitForma.submit('forma')">
 ###				Submit
 ###			</button>
 ###		</biv>
 ###	</div>
 ### </form>
 ###
 */
function SubmitForm(){
	this.submit = function(formaID){
		var test = 1;
		var input = document.forms[formaID].getElementsByTagName('input');
		var area = document.forms[formaID].getElementsByTagName('textarea');
		var inputL = input.length,
			areaL = area.length;
		for(i=0; i< Math.max(inputL,areaL); i++){
			if(i < inputL && input[i].getAttribute('id'))
				test = succErr(input[i].getAttribute('type')=='email'?'email':'input',input[i].getAttribute('id'), test);
			if(i < areaL && area[i].getAttribute('id')) 
				test = succErr('area',area[i].getAttribute('id'), test);
		}
		if(test) $('#'+formaID).submit();
		else alert('Popunite sve podatke.');
	}
	var testEmail = function(email){
		var i1 = email.indexOf('@'),
			i2 = email.indexOf('.');
		if((i1 < 1 || i2 < 1) || (i1 > i2)) return false;
		else return true;
	}
	var succErr = function(tip, ime, t){
		var polje = tip == 'input' || tip == 'email' ? 'input[name="'+ime+'"]' : tip == 'area' ? '#'+ime : null;
		if($(polje).val().length > 2 && (tip=='email'?testEmail($(polje).val()):true)){
			$('#d'+ime).removeClass('has-error');
			$('#d'+ime).addClass('has-success');
			$('#s'+ime).removeClass('glyphicon-remove');
			$('#s'+ime).addClass('glyphicon-ok');
			return t;
		}else{
			$('#d'+ime).removeClass('has-success');
			$('#d'+ime).addClass('has-error');
			$('#s'+ime).removeClass('glyphicon-ok');
			$('#s'+ime).addClass('glyphicon-remove');
			return 0;
		}
	}
}