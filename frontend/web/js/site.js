jQuery('#agenda-calendar a').on('click',function(e){
	var agendaSet = jQuery(this).parents('.agenda-set');
	agendaSet.find('.agenda-day').addClass('hidden');
	agendaSet.find('.selected').removeClass('selected');
	if(agendaSet.find('div[data-day=' + jQuery(this).data('day')+ ']').length)
		agendaSet.find('div[data-day=' + jQuery(this).data('day')+ ']').removeClass('hidden');
	else
		agendaSet.find('#empty-set').removeClass('hidden');
	jQuery(this).parent().addClass('selected');
	
	e.preventDefault();
	if(window.parent && window.parent.resizeIframe)
		window.parent.resizeIframe();
});
jQuery(".milonga-description .more-link").on("click",function(e){
	e.preventDefault();
	var desc_elt = jQuery(this).parent();
	desc_elt.find(".more-content").css("opacity", 0).css("height", "auto").slideDown("normal",function(){
		desc_elt.find(".less-link").show();
	}).animate(
		{ opacity: 1 },
		{ queue: false, duration: "normal" }
		);
});

jQuery(".milonga-description .less-link").on("click",function(e){
	// e.preventDefault();
	var desc_elt = jQuery(this).parent();
	desc_elt.find(".more-content").animate(
		{ opacity: 0, height: "5.1em" },
		{ queue: false, duration: "normal", complete: function(){ desc_elt.find(".more-link").show();jQuery(this).hide();} }
	);
});
jQuery('#navbar-agenda li a').on('click',function(e){
	e.preventDefault();
	// console.log(jQuery(this).data('set'));
	var dayContainer = jQuery(this).parents('.agenda-day');
	dayContainer.find('.set').hide();
	dayContainer.find('.set[id="'+jQuery(this).data('set')+'"]').show();
	jQuery(this).parents('.navbar-nav').find('li').removeClass('active');
	jQuery(this).parent().addClass('active');
});
jQuery('[data-toggle="popover"]').popover();