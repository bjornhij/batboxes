/* Global vars */
var cord_modal_state = "not_set";

$(function(){
	
	/* Default helper function to hide all js fields, used for slideToggles */
	$('.hidden-js').hide();	
	
});
$(document).ready(function(){
	
	/* Style fixes */
	$('.collapse.navbar-collapse > ul').removeClass();
	$('.collapse.navbar-collapse > ul').addClass('nav navbar-nav');
	
	/* Slide-toggles div visibilty */
	$(document).on('click', '[data-slide-toggle]', function() { 
		$('.' + $(this).data('slide-toggle')).slideToggle();
	});

});