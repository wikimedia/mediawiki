/*
 * jQuery.appear
 * http://code.google.com/p/jquery-appear/
 *
 * Copyright (c) 2009 Michael Hixson
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
*/
(function($) {
	 
	$.fn.appear = function(fn, options) {
		 
		var settings = $.extend({
 
			//arbitrary data to pass to fn
			data: undefined,
 
			//call fn only on the first appear?
			one: true
			 
		}, options);
		 
		return this.each(function() {
		 
			var t = $(this);
			 
			//whether the element is currently visible
			t.appeared = false;
			 
			if (!fn) {
 
				//trigger the custom event
				t.trigger('appear', settings.data);
				return;
			}
			 
			var w = $(window);
			 
			//fires the appear event when appropriate
			var check = function() {
 
				//is the element hidden?
				if (!t.is(':visible')) {
					 
					//it became hidden
					t.appeared = false;
					return;
				}
 
				//is the element inside the visible window?
				var a = w.scrollLeft();
				var b = w.scrollTop();
				var o = t.offset();
				var x = o.left;
				var y = o.top;
				 
				if (y + t.height() >= b && 
						y <= b + w.height() &&
						x + t.width() >= a && 
						x <= a + w.width()) {
 
					//trigger the custom event
					if (!t.appeared) t.trigger('appear', settings.data);
					 
				} else {
 
					//it scrolled out of view
					t.appeared = false;
				}
			};
 
			//create a modified fn with some additional logic
			var modifiedFn = function() {
				 
				//mark the element as visible
				t.appeared = true;
 
				//is this supposed to happen only once?
				if (settings.one) {
 
					//remove the check
					w.unbind('scroll', check);
					var i = $.inArray(check, $.fn.appear.checks);
					if (i >= 0) $.fn.appear.checks.splice(i, 1);
				}
 
				//trigger the original fn
				fn.apply(this, arguments);
			};
			 
			//bind the modified fn to the element
			if (settings.one) t.one('appear', settings.data, modifiedFn);
			else t.bind('appear', settings.data, modifiedFn);
			 
			//check whenever the window scrolls
			w.scroll(check);
			 
			//check whenever the dom changes
			$.fn.appear.checks.push(check);
			 
			//check now
			(check)();
		});
	};
	 
	//keep a queue of appearance checks
	$.extend($.fn.appear, {
		 
		checks: [],
		timeout: null,
 
		//process the queue
		checkAll: function() {
			var length = $.fn.appear.checks.length;
			if (length > 0) while (length--) ($.fn.appear.checks[length])();
		},
 
		//check the queue asynchronously
		run: function() {
			if ($.fn.appear.timeout) clearTimeout($.fn.appear.timeout);
			$.fn.appear.timeout = setTimeout($.fn.appear.checkAll, 20);
		}
	});
	 
	//run checks when these methods are called
	$.each(['append', 'prepend', 'after', 'before', 'attr', 
					'removeAttr', 'addClass', 'removeClass', 'toggleClass', 
					'remove', 'css', 'show', 'hide'], function(i, n) {
		var old = $.fn[n];
		if (old) {
			$.fn[n] = function() {
				var r = old.apply(this, arguments);
				$.fn.appear.run();
				return r;
			}
		}
	});
	 
})(jQuery);