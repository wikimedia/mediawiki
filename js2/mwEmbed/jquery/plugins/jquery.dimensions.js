/* Copyright (c) 2007 Paul Bakaus (paul.bakaus@googlemail.com) and Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * $LastChangedDate: 2007-09-10 19:38:31 -0700 (Mon, 10 Sep 2007) $
 * $Rev: 3238 $
 *
 * Version: @VERSION
 *
 * Requires: jQuery 1.2+
 */

(function($){

$.dimensions = {
	version: '@VERSION'
};

// Create innerHeight, innerWidth, outerHeight and outerWidth methods
$.each( [ 'Height', 'Width' ], function(i, name){

	// innerHeight and innerWidth
	$.fn[ 'inner' + name ] = function() {
		if (!this[0]) return;

		var torl = name == 'Height' ? 'Top'	: 'Left',  // top or left
			borr = name == 'Height' ? 'Bottom' : 'Right'; // bottom or right

		return this[ name.toLowerCase() ]() + num(this, 'padding' + torl) + num(this, 'padding' + borr);
	};

	// outerHeight and outerWidth
	$.fn[ 'outer' + name ] = function(options) {
		if (!this[0]) return;

		var torl = name == 'Height' ? 'Top'	: 'Left',  // top or left
			borr = name == 'Height' ? 'Bottom' : 'Right'; // bottom or right

		options = $.extend({ margin: false }, options || {});

		return this[ name.toLowerCase() ]()
				+ num(this, 'border' + torl + 'Width') + num(this, 'border' + borr + 'Width')
				+ num(this, 'padding' + torl) + num(this, 'padding' + borr)
				+ (options.margin ? (num(this, 'margin' + torl) + num(this, 'margin' + borr)) : 0);
	};
});

// Create scrollLeft and scrollTop methods
$.each( ['Left', 'Top'], function(i, name) {
	$.fn[ 'scroll' + name ] = function(val) {
		if (!this[0]) return;

		return val != undefined ?

			// Set the scroll offset
			this.each(function() {
				this == window || this == document ?
					window.scrollTo(
						name == 'Left' ? val : $(window)[ 'scrollLeft' ](),
						name == 'Top'  ? val : $(window)[ 'scrollTop'  ]()
					) :
					this[ 'scroll' + name ] = val;
			}) :

			// Return the scroll offset
			this[0] == window || this[0] == document ?
				self[ (name == 'Left' ? 'pageXOffset' : 'pageYOffset') ] ||
					$.boxModel && document.documentElement[ 'scroll' + name ] ||
					document.body[ 'scroll' + name ] :
				this[0][ 'scroll' + name ];
	};
});

$.fn.extend({
	position: function() {
		var left = 0, top = 0, elem = this[0], offset, parentOffset, offsetParent, results;

		if (elem) {
			// Get *real* offsetParent
			offsetParent = this.offsetParent();

			// Get correct offsets
			offset	   = this.offset();
			parentOffset = offsetParent.offset();

			// Subtract element margins
			offset.top  -= num(elem, 'marginTop');
			offset.left -= num(elem, 'marginLeft');

			// Add offsetParent borders
			parentOffset.top  += num(offsetParent, 'borderTopWidth');
			parentOffset.left += num(offsetParent, 'borderLeftWidth');

			// Subtract the two offsets
			results = {
				top:  offset.top  - parentOffset.top,
				left: offset.left - parentOffset.left
			};
		}

		return results;
	},

	offsetParent: function() {
		var offsetParent = this[0].offsetParent;
		while ( offsetParent && (!/^body|html$/i.test(offsetParent.tagName) && $.css(offsetParent, 'position') == 'static') )
			offsetParent = offsetParent.offsetParent;
		return $(offsetParent);
	}
});

var num = function(el, prop) {
	return parseInt($.css(el.jquery?el[0]:el,prop))||0;
};

})(jQuery);