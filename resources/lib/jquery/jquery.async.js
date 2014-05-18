/*
 * jQuery Asynchronous Plugin 1.0
 *
 * Copyright (c) 2008 Vincent Robert (genezys.net)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */
(function($){

// opts.delay : (default 10) delay between async call in ms
// opts.bulk : (default 500) delay during which the loop can continue synchronously without yielding the CPU
// opts.test : (default true) function to test in the while test part
// opts.loop : (default empty) function to call in the while loop part
// opts.end : (default empty) function to call at the end of the while loop
$.whileAsync = function(opts) {
	var delay = Math.abs(opts.delay) || 10,
		bulk = isNaN(opts.bulk) ? 500 : Math.abs(opts.bulk),
		test = opts.test || function(){ return true; },
		loop = opts.loop || function(){},
		end = opts.end || function(){};
	
	(function(){

		var t = false,
			begin = new Date();
			
		while( t = test() ) {
			loop();
			if( bulk === 0 || (new Date() - begin) > bulk ) {
				break;
			}
		}
		if( t ) {
			setTimeout(arguments.callee, delay);
		}
		else {
			end();
		}
		
	})();
};

// opts.delay : (default 10) delay between async call in ms
// opts.bulk : (default 500) delay during which the loop can continue synchronously without yielding the CPU
// opts.loop : (default empty) function to call in the each loop part, signature: function(index, value) this = value
// opts.end : (default empty) function to call at the end of the each loop
$.eachAsync = function(array, opts) {
	var	i = 0,
		l = array.length,
		loop = opts.loop || function(){};
	
	$.whileAsync(
		$.extend(opts, {
			test: function() { return i < l; },
			loop: function() {
				var val = array[i];
				return loop.call(val, i++, val);
			}
		})
	);
};

$.fn.eachAsync = function(opts) {
	$.eachAsync(this, opts);
	return this;
}

})(jQuery);