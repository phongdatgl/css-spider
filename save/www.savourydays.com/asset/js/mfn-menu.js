/*
@Name:		Horizontal multilevel menu
@Author:    Muffin Group
@WWW:       www.muffingroup.com
@Version:   1.2.2
*/

(function($){
	$.fn.extend({
		muffingroup_menu: function(options) {
			
			var defaults = {
				delay       : 50,
				hoverClass  : 'hover',
				arrows      : true,
				animation   : 'fade',
				addLast		: false
			};
			
			options = $.extend(defaults, options);
	        
			var menu = $(this);
			menu.find("li:has(ul)").addClass("submenu");
			
			if(options.arrows) {
				menu.find("li ul li:has(ul) > a").append("<span style='display: block; position: absolute; right: 20px; top: 3px;'> &rsaquo;</span>")
			}
	
			menu.find("li").hover(function() {
				$(this).addClass(options.hoverClass);
				if (options.animation == "fade") {
					$(this).children("ul").fadeIn(options.delay);
				} else if (options.animation == "toggle") {
					$(this).children("ul").slideToggle(options.delay);
				};
			}, function(){
				$(this).removeClass(options.hoverClass);
				if (options.animation == "fade") {
					$(this).children("ul").fadeOut(options.delay);
				} else if (options.animation == "toggle") {
					$(this).children("ul").slideToggle(options.delay);
				};
			});
			
			if(options.addLast) {
				$("> li:last-child", menu)
					.addClass("last")
					.prev()
						.addClass("last")
						.prev()
							.addClass("last");
				$(".submenu ul li:last-child", menu).addClass("last-item");
			}
	
	    }
	});
})(jQuery);