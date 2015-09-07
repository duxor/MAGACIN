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
 ###			<button type="button" class="btn btn-lg btn-success" onClick="SubmitForm.submit('forma')">
 ###				Submit
 ###			</button>
 ###		</biv>
 ###	</div>
 ### </form>
 ###
 */
var SubmitForm = {
	submit: function(formaID){
		if(this.check(formaID)) $('#'+formaID).submit();
		else alert('Popunite sve podatke.');
	},
	check:function(formaID){
		var test=1;
		var inputi = $('#'+formaID+' :input:visible[id]');
		for(i=0; i< inputi.length; i++)test = this.succErr(inputi[i], test);
		return test;
	},
	testEmail: function(email){
		var i1 = email.indexOf('@'),
			i2 = email.indexOf('.');
		if((i1 < 1 || i2 < 1) || (i1 > i2)) return false;
		else return true;
	},
	succErr: function(input, t){
		if($(input).val().length > 2 && ($(input).attr('type')=='email'?this.testEmail($(input).val()):true)){
			$('#d'+input.name).removeClass('has-error');
			$('#d'+input.name).addClass('has-success');
			$('#s'+input.name).removeClass('glyphicon-remove');
			$('#s'+input.name).addClass('glyphicon-ok');
			return t;
		}else{
			$('#d'+input.name).removeClass('has-success');
			$('#d'+input.name).addClass('has-error');
			$('#s'+input.name).removeClass('glyphicon-ok');
			$('#s'+input.name).addClass('glyphicon-remove');
			return 0;
		}
	}
}
/*#
 ### Autor: Dusan Perisci
 ### Home: dusanperisic.com
 ###
 ### Napomena: 	Klasa je pisana kao dodatak Laravel framework-a
 ### ------------------------------------------------------------------
 ### Primjer:
 ### HTML:  <div id="poruka" style="display: none"></div>
 ###        <div id="wait" style="display:none"><center><i class='icon-spin6 animate-spin' style="font-size: 350%"></i></center></div>
 ###        <div id="hide">
 ###            {!!Form::hidden('_token',csrf_token())!!}
 ###            {!!Form::text('prezime',null,['class'=>'form-control'])!!}
 ###            {!!Form::text('ime',null,['class'=>'form-control'])!!}
 ###            {!!Form::button('<span class="glyphicon glyphicon-save"></span> Sačuvaj',['class'=>'btn btn-lg btn-primary','onclick'=>'Komunikacija.posalji("/url","podaciID","poruka","wait","hide")'])!!}
 ###        </div>
 ###
 ### LARAVEL metoda:
 ### 	public function postTest(){
 ###        $podaci=json_decode(Input::get('podaci'));
 ###		return json_encode(['msg'=>'prezime='.$podci->prezime.' ime='.$podaci->ime,'check'=>1]);
 ###	}
 ### VARIJABLE:
 ### url = adresa kojoj se prosledjuju podaci
 ### podaciID = promjenjiva koja sadrzi ID elementa koji obuhvata sve input elemente za prenos podataka, ukljucujuci i _token=csrf_token()
 ### poruka = ID elementa u kome ce da se ispisuje poruka
 ### wait = ID elementa koji sadrzi wait animaciju
 ### hide = ID elementa ciji sadrzaj treba da se sakrije dok je wait aktivan
 ###
 */
var Komunikacija = {
	posalji: function(url,podaciID,poruka,wait,hide){
		var podaci=this.podaci('',null,podaciID,{});
		$('#'+hide).css('display','none');
		$('#'+wait).fadeToggle();
		$.post(url,
			{
				_token:podaci['_token'],
				podaci:JSON.stringify(podaci)
			},
			function(data){
				data=JSON.parse(data);
				$('#'+poruka).html('<div class="alert alert-'+ (data['check']?'success':'danger') +'" role="alert">'+data['msg']+'</div>');
				$('#'+wait).fadeToggle();
				$('#'+poruka).fadeToggle('slow');
				window.setTimeout(function(){
					$('#'+poruka).fadeToggle('slow');
					$('#'+hide).fadeToggle('slow')
				},5000);
			}
		);
	},
	podaci:function(i,inputi,podaciID,podaci){
		if(inputi==null) {
			var inputi = $('#' + podaciID + ' :input');
			i = inputi.length - 1;
		}
		podaci[inputi[i].name]=inputi[i].value;
		if(i==0) return podaci;
		return this.podaci(i-1,inputi,null,podaci);
	},
	proslijedi:function(url,token,podaci){
		$.post(url,{
			_token:token,
			podaci:JSON.stringify(podaci)
		},function(data){
			return true;
		});
	},
	proslijediSaPrikzom:function(url,token,podaci,poruka,wait,hide){
		$('#'+hide).css('display','none');
		$('#'+wait).fadeToggle();
		$.post(url,{
			_token:token,
			podaci:JSON.stringify(podaci)
		},function(data){
			data=JSON.parse(data);
			$('#'+poruka).html('<div class="alert alert-'+ (data['check']?'success':'danger') +'" role="alert">'+data['msg']+'</div>');
			$('#'+wait).fadeToggle();
			$('#'+poruka).fadeToggle('slow');
			window.setTimeout(function(){
				$('#'+poruka).fadeToggle('slow');
				$('#'+hide).fadeToggle('slow')
			},5000);
		});
	}
}