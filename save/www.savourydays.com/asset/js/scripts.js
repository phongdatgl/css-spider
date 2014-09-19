jQuery(document).ready(function() {
	
	/* ---------------------------------------------------------------------------
	 * Fancybox
	 * --------------------------------------------------------------------------- */
	jQuery("a.fancybox, .gallery-icon a, .the_content .attachment a").fancybox({
		'overlayShow'	: false,
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic'
	});
	
	jQuery("a.iframe").fancybox({
		'transitionIn'	: 'none',
		'transitionOut'	: 'none'
	});
	
	
	/* ---------------------------------------------------------------------------
	 * WP Gallery
	 * --------------------------------------------------------------------------- */
	jQuery(".gallery-icon a").attr("rel","gallery");
		
	
	/* ---------------------------------------------------------------------------
	 * Add classes last
	 * --------------------------------------------------------------------------- */
	jQuery(".Recent_comments li:last-child, .Latest_posts li:last-child, .Twitter li:last-child, #Subheader ul.breadcrumbs li:last-child").addClass("last");
	jQuery(".commentlist li li .comment-body:last-child").addClass("last");
	jQuery(".commentlist li .comment-body:last-child").addClass("lastBorder");
	
	
	/* ---------------------------------------------------------------------------
	 * Add classes first/last
	 * --------------------------------------------------------------------------- */
	jQuery(".Twitter ul li:first-child").addClass("first");
	jQuery(".Projects .column:first-child").addClass("alpha");
	jQuery(".Projects .column:last-child").addClass("omega");
	
	
	/* ---------------------------------------------------------------------------
	 * Main menu
	 * --------------------------------------------------------------------------- */
	jQuery("#menu > ul > li").each(function() {
		
		var liWidth = jQuery(this).innerWidth();
		jQuery(this).css("width", liWidth);
		jQuery(this).children("a").css("width", liWidth - 40);
		jQuery(this).find("a").append('<span></span><div class="arrow"><em class="a1"></em><em class="a2"></em><em class="a3"></em><em class="a4"></em><em class="a5"></em></div>');
	
	});
	
	jQuery("#menu > ul").muffingroup_menu({
		delay: 0,
		hoverClass: 'hover',
		arrows: true,
		animation: 'fade'
	});
	
	
	/* ---------------------------------------------------------------------------
	 * Testimonial
	 * --------------------------------------------------------------------------- */
	jQuery(".testimonial ul.slider").responsiveSlides({
		pager: true
	});
		
	var testimonial_slider_control  = jQuery('.testimonial ul.rslides_tabs li');
	var testimonial_slider_ControlsWidth = ((testimonial_slider_control.length * (testimonial_slider_control.innerWidth() + 6)) / 2);
	jQuery(".testimonial ul.rslides_tabs").css("margin-left", - testimonial_slider_ControlsWidth);
	
	
	/* ---------------------------------------------------------------------------
	 * Tabs
	 * --------------------------------------------------------------------------- */
	jQuery(".jq-tabs").tabs();
	
	
	/* ---------------------------------------------------------------------------
	 * Accordion
	 * --------------------------------------------------------------------------- */
	jQuery(".jq-accordion").accordion({
		autoHeight: false
	});
	
	
	/* ---------------------------------------------------------------------------
	 * Gallery
	 * --------------------------------------------------------------------------- */
	jQuery(".gallery-item img").css("height","auto").css("width","100%");
	
	
	/* ---------------------------------------------------------------------------
	 * IE placeholder fix
	 * --------------------------------------------------------------------------- */
	jQuery("[placeholder]").each(function(){
	  if(jQuery(this).val()=="" && jQuery(this).attr("placeholder")!=""){
		jQuery(this).val(jQuery(this).attr("placeholder"));
		jQuery(this).focus(function(){
		  if(jQuery(this).val()==jQuery(this).attr("placeholder")) jQuery(this).val("");
		});
		jQuery(this).blur(function(){
		  if(jQuery(this).val()=="") jQuery(this).val(jQuery(this).attr("placeholder"));
		});
	  }
	});
	

	/* ---------------------------------------------------------------------------
	 * Social
	 * --------------------------------------------------------------------------- */
	var social_item  = jQuery('ul.social li');
	var social_width = (social_item.length * (social_item.innerWidth() + 6));
	jQuery("ul.social").css("width", social_width);
	

	/* ---------------------------------------------------------------------------
	 * Image frames
	 * --------------------------------------------------------------------------- */
	jQuery(".wp-caption a").hover( function() {
		jQuery(this).find(".overlay").fadeIn(100);
		jQuery(this).find("span.control_button").fadeIn(300);
	}, function() {
		jQuery(this).find(".overlay").fadeOut(100);
		jQuery(this).find("span.control_button").fadeOut(200);
	});
	
	/* ---------------------------------------------------------------------------
	 * Faq
	 * --------------------------------------------------------------------------- */
	jQuery(".faq .question h5:not(:first)").prepend("<i class='icon-chevron-down'></i>");
	jQuery(".faq .question:first").addClass("first").children("h5").prepend("<i class='icon-chevron-up'></i>");
	jQuery(".faq .question:not(:first)").children(".answer").hide();
	jQuery(".faq .question:first").addClass("active");
	
	jQuery(".faq .question > h5").click(function() {
		if(jQuery(this).parent().hasClass("active")) {
			jQuery(this).parent().removeClass("active").children(".answer").slideToggle(200);
			jQuery(this).children("i").removeClass("icon-chevron-up").addClass("icon-chevron-down");
		}
		else
		{
			jQuery(".faq .question").each(function(index) {
				if(jQuery(this).hasClass("active")) {
					jQuery(this).removeClass("active").children(".answer").slideToggle(200);
					jQuery(this).find("i").removeClass("icon-chevron-up").addClass("icon-chevron-down");
				}
			});
			jQuery(this).parent().addClass("active");
			jQuery(this).next(".answer").slideToggle(200);
			jQuery(this).children("i").removeClass("icon-chevron-down").addClass("icon-chevron-up");
		}
	});
	
	/* ---------------------------------------------------------------------------
	 * Clients
	 * --------------------------------------------------------------------------- */
	function ClientsSlider_initCallback(carousel) {
	    jQuery('#mycarousel-next').bind('click', function() {
	        carousel.next();
	        return false;
	    });
	    jQuery('#mycarousel-prev').bind('click', function() {
	        carousel.prev();
	        return false;
	    });
	};
		
	function ClientsSlider_init(){
		
		if (jQuery(window).width() < 479) {
			visibleEl = 1
		} else if (jQuery(window).width() < 767) {
			visibleEl = 2
		} else if (jQuery(window).width() < 959) {
			visibleEl = 4
		} else {
			visibleEl = 6
		}
	    jQuery(".Clients_inside > ul").jcarousel({
	        scroll: 1,
			visible: visibleEl,
	        initCallback: ClientsSlider_initCallback,
	        buttonNextHTML: null,
	        buttonPrevHTML: null
	    });
	}
	
	ClientsSlider_init();
	
	/* ---------------------------------------------------------------------------
	 * Go to top
	 * --------------------------------------------------------------------------- */
	jQuery("a.go_to_top").click(function() {
		jQuery("html, body").animate( {
			scrollTop : 0
		}, 1000);
		return false;
	});
	
});