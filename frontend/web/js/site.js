jQuery('#agenda-calendar a').on('click',function(e){
	console.log('Hello');
	var agendaSet = jQuery(this).parents('.agenda-set');
	agendaSet.find('.agenda-day').addClass('hidden');
	agendaSet.find('.selected').removeClass('selected');
	agendaSet.find('div[data-day=' + jQuery(this).data('day')+ ']').removeClass('hidden');
	jQuery(this).parent().addClass('selected');
	
	e.preventDefault();
	if(window.parent && window.parent.resizeIframe)
		window.parent.resizeIframe();
});
jQuery(".milonga-description .more-link").on("click",function(e){
	e.preventDefault();
	var desc_elt = jQuery(this).parent();
	jQuery(this).hide();
	desc_elt.find(".more-content").css("opacity", 0).slideDown("normal",function(){
		desc_elt.find(".less-link").show();
		if(window.parent && window.parent.resizeIframe)
			window.parent.resizeIframe();
	}).animate(
		{ opacity: 1 },
		{ queue: false, duration: "normal" }
		);
});

jQuery(".milonga-description .less-link").on("click",function(e){
	e.preventDefault();
	var desc_elt = jQuery(this).parent();
	jQuery(this).fadeOut();
	desc_elt.find(".more-content").slideUp("normal",function(){ 
		desc_elt.find(".more-link").show();
		if(window.parent && window.parent.resizeIframe)
			window.parent.resizeIframe();
	}).animate(
		{ opacity: 0 },
		{ queue: false, duration: "normal" }
		);
});
