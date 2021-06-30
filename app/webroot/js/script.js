// G5 Framework
// http://Framework.GregBabula.info
// http://GregBabula.info/framework.php
( function($) { // 'bodyGuard' function para prevenir erro do $(document).ready devido ao Prototype e Scriptaculous

$(window).load(function(){
	var hg = $('#gallery').height();
	var hc = $('section.content').height();
	if( hg > hc )
	{
		//alert(hg+', '+hc);
		$('.content').css(
		{
			'height' : hg+46,
			'min-height' : hg+46
		})
	}
});

$(document).ready(function() {
	if( $('body').find('#slider') )
	{
		$('#slider').kwicks({
			max : 820,
			spacing : 0,
			easing : "easeInExpo"
		});
		$('#pop').find('span').click(function(){
			showHideLayer('pop');
			clearTimeout(cont);
		});
	};

	if( $('body').find('#gallery').length )
	{
		$('#gallery a').lightBox();
	}

	if($.browser.msie && $.browser.version.substr(0,1)<9){
		$('html').removeClass('ie8').addClass('ie7');
		$('input[type=text], textarea').addClass('ie');
	}

	$('input[type!=submit], textarea').focus(function(){
		$(this).parent().parent().css('background-color', '#FCFCBE')
	});
	$('input[type!=submit], textarea').blur(function(){
		$(this).parent().parent().css('background', 'none')
	});
	
	//Smooth Scroll To Top
	$(".return-top").click(function() {
		$("html, body").animate({
			scrollTop: $($(this).attr("href")).offset().top + "px"
		}, {
			duration: 250,
			easing: "swing"
		});
		return false;
	});

    //Nav IE last-child fix
     $("header#top nav ul li:last-child a").css("margin-right","0");

    //Remove padding from last paragraph
     $(".cols p:last-child").css("padding-bottom","0");

    //Tipsy
  	//$(function() {
	//    $('.tooltip').tipsy();
	//    
	//    $('.tooltip-north').tipsy({gravity: 'n'});
	//    $('.tooltip-south').tipsy({gravity: 's'});
	//    $('.tooltip-east').tipsy({gravity: 'e'});
	//    $('.tooltip-west').tipsy({gravity: 'w'});
	//    
	//    $('.tooltip-fade').tipsy({fade: true});
  	//});

	//HTML5 Placeholder Fallback

	// Create input element to do tests
	var input = document.createElement('input');

	// Add placeholder support for non HTML5 browsers
	var support_placeholder = 'placeholder' in input;
	if(!support_placeholder){
		$(':input[placeholder]').each(function() {
			var $$ = $(this);
			if($$.val() === '') {
		   
				$$.addClass('placeholder');
				$$.val($$.attr('placeholder'));
			}
			$$.focus(function() {
				$$.addClass('focus');
				if($$.val() === $$.attr('placeholder')) {
					$$.val('');
					$$.removeClass('placeholder');
				}
			}).blur(function() {
				$$.removeClass('focus');
				if($$.val() === '') {
					$$.addClass('placeholder');
					$$.val($$.attr('placeholder'));
				}
			});
		});
	}

	// Add autofocus support for non HTML5 browsers
	var support_autofocus = 'autofocus' in input;
	if(!support_autofocus){
		$('input[autofocus]').eq(0).focus();
	}else{
		// Fix for opera
		$('input[autofocus]').eq(0).val('');	
		$('input[autofocus]').eq(0).removeClass('placeholder');
	}

	// Handler form validation
	$('input,textarea').keyup(function() {
		validate(this);
	});
});
} ) ( jQuery );