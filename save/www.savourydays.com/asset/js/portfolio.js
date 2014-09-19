jQuery(document).ready(function() {
	
	jQuery(".portfolio_item .photo img").addClass('hidden');
	
	// clear before each portfolio row --------------------------------
	jQuery('.portfolio .one-second:nth-child(2n+3)').css("clear", "both");	
	jQuery('.portfolio .one-third:nth-child(3n+4)').css("clear", "both");	
	jQuery('.portfolio .one-fourth:nth-child(4n+5)').css("clear", "both");	

	// portfolio item overlay -----------------------------------------
	function mfn_image_overlay(){
		var height = jQuery(".portfolio_item .photo img").height();
		var width = jQuery(".portfolio_item .photo img").width();
		jQuery(".portfolio_item .photo .overlay").height(height).width(width);
	}
	
	jQuery(window).load(function () {
		mfn_image_overlay();
	});
	
	jQuery(window).resize(function(){	
		mfn_image_overlay();
	});
	
	jQuery(".portfolio_item").hover( function() {
		jQuery(this).find(".photo .overlay").fadeIn(100);
		jQuery(this).find(".photo .sep").fadeIn(300);
		jQuery(this).find(".photo a.fullscreen").stop().animate({ left: '47%' }, { duration: 300 });
		jQuery(this).find(".photo a.details").stop().animate({ right: '47%' }, { duration: 300 });
	}, function() {
		jQuery(this).find(".photo .overlay").fadeOut(100);
		jQuery(this).find(".photo .sep").fadeOut(100);
		jQuery(this).find(".photo a.fullscreen").stop().animate({ left: '-10%' }, { duration: 200 });
		jQuery(this).find(".photo a.details").stop().animate({ right: '-10%' }, { duration: 200 });
	});
	
	// isotope ----------------------------------------------------------
	jQuery('.portfolio-isotope .select_category a').click(function(e){
		e.preventDefault();

		var filter = jQuery(this).attr('rel');
		jQuery('.portfolio-isotope .portfolio-wrapper').isotope({ filter: filter });

		jQuery(this).parents('ul').find('li.current-cat').removeClass('current-cat');
		jQuery(this).parent().addClass('current-cat');
	});

});

var image = 0;
var int = 0;

jQuery(window).load(function() {
	jQuery('.portfolio-isotope .portfolio-wrapper').isotope({
		itemSelector: '.column',
		layoutMode: 'fitRows'
	});
	
	var int = setInterval("showPreviews(image)",200);
});

function showPreviews() {
	var QuantityOfProjects = jQuery(".portfolio_item .photo img").lenght;
	if(image >= QuantityOfProjects) {
		clearInterval(image);
	}
	jQuery(".portfolio_item .photo img.hidden").eq(0).removeClass('hidden').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0},200);
	image++;
}