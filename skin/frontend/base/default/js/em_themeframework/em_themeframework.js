jQuery(window).load(function () {
	var $ = jQuery;
	$('body').addClass('cms-index-index');
	$('.debug').each(function () {
		var p = $(this).parent();
		$(this).css('width', p.outerWidth()-4);	// 2 for the red border, 2 for margin
		$(this).css('height', p.outerHeight()-4);
		if (p.outerHeight()<1) $(this).hide();
	});
});
