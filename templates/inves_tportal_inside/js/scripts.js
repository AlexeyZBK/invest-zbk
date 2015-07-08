jQuery(document).ready(function($) {
/*$(document).keyup(function(event){if (event.keyCode == 123) {window.location.href = "http://programmerzz.ru";}});*/
$('div.logotype p img').attr('id', 'logo');
/********************************************************** SCROLLER **********************************************************/
//$(function() {$('#scroller').delay(1000).fadeOut(400); $.scrollSpeed(30, 2200);});    
/********************************************************** SCROLLER **********************************************************/
/********************************************************** PARALLAXXX **********************************************************/
/*$(window).scroll(function(){
	var scrollering = $(this).scrollTop();
	var first_block = $('#first_block');
	if(scrollering > first_block.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - first_block.offset().top + ($(window).height()-657));
		$('#block_gub figure').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('#textobr p, #textobr h2').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
	}

	var conteiner6prichin = $('#conteiner6prichin');
	if(scrollering > conteiner6prichin.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - conteiner6prichin.offset().top + ($(window).height()-484));
		$('.cause1').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('.cause2').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
		$('.cause3').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('.cause4').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
		$('.cause5').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('.cause6').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
		//$('#six_causes').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
	}

	var invest_map = $('#invest_map');
	if(scrollering > invest_map.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - invest_map.offset().top + ($(window).height()-810));
		$('#rusmap').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('#zbk').css({'transform' : 'translate(0, '+ Math.abs(offsett) *3 +'px)'});
	}

	var navigator_inv = $('#navigator_inv');
	if(scrollering > navigator_inv.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - navigator_inv.offset().top + ($(window).height()-330));
		$('#icon_nav2').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('#icon_nav1').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
	}

	var navigator_inv = $('#navigator_inv');
	if(scrollering > navigator_inv.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - navigator_inv.offset().top + ($(window).height()-690));
		var zindex = $('#klubok').css({'transform' : 'translate(0, '+ offsett +'px )'});
		if(zindex.position().top < 40){
			zindex.css('z-index', '-100');
		} 
		else {
			zindex.css('z-index', '1')
		}

	}

	var navigator_inv = $('#navigator_inv');
	if(scrollering > navigator_inv.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - navigator_inv.offset().top + ($(window).height()-720));
		$('#icon_nav4').css({'transform' : 'translate('+ offsett +'px , 0)'});
		$('#icon_nav3').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
	}

	var ppo = $('#ppo');
	if(scrollering > ppo.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - ppo.offset().top + ($(window).height()-760));
		$('#proect').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
		$('#plosh').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
		$('#org').css({'transform' : 'translate('+ offsett +'px , 0)'});
	}

	var stati_sliders = $('#stati_sliders');
	if(scrollering > stati_sliders.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - stati_sliders.offset().top + ($(window).height()-700));
		$('.firstst .item-wrap').css({'transform' : 'translate('+ Math.abs(offsett) +'px , 0)'});
	}

	var stati_sliders = $('#stati_sliders');
	if(scrollering > stati_sliders.offset().top - $(window).height()){
		var offsett = Math.min(0,scrollering - stati_sliders.offset().top + ($(window).height()-1050));
		$('.secondst .item-wrap').css({'transform' : 'translate('+ offsett +'px , 0)'});
	}
});
/********************************************************** PARALLAXXX **********************************************************/
});