jQuery('#agenda-calendar a').on('click',function(e){
	var agendaSet = jQuery(this).parents('.agenda-set');
	agendaSet.find('.agenda-day').addClass('hidden');
	agendaSet.find('.selected').removeClass('selected');
	agendaSet.find('div[data-day=' + jQuery(this).data('day')+ ']').removeClass('hidden');
	jQuery(this).parent().addClass('selected');
	window.parent.resizeIframe();
	e.preventDefault();
});