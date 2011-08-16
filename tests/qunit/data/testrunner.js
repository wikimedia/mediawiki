( function( $ ) {

/**
 * Add bogus to url to prevent IE crazy caching
 *
 * @param value {String} a relative path (eg. 'data/defineTestCallback.js' or 'data/test.php?foo=bar')
 * @return {String} Such as 'data/defineTestCallback.js?131031765087663960'
 */
QUnit.fixurl = function(value) {
	return value + (/\?/.test(value) ? "&" : "?") + new Date().getTime() + "" + parseInt(Math.random()*100000);
};

/**
 *  Load TestSwarm agent
 */
if ( QUnit.urlParams.swarmURL  ) {
	document.write("<scr" + "ipt src='" + QUnit.fixurl( 'data/testwarm.inject.js' ) + "'></scr" + "ipt>");
}

/**
 * Load completenesstest
 */
if ( QUnit.urlParams.completenesstest ) {

	// Return true to ignore
	var mwTestIgnore = function( val, tester, funcPath ) {

		// Don't record methods of the properties of constructors,
		// to avoid getting into a loop (prototype.constructor.prototype..).
		// Since we're therefor skipping any injection for
		// "new mw.Foo()", manually set it to true here.
		if ( val instanceof mw.Map ) {
			tester.methodCallTracker['Map'] = true;
			return true;
		}

		// Don't record methods of the properties of a jQuery object
		if ( val instanceof $ ) {
			return true;
		}

		return false;
	};

	var mwTester = new CompletenessTest( mw, mwTestIgnore );
}

/**
 * Add-on assertion helpers
 */
// Define the add-ons
var addons = {

	// Expect boolean true
	assertTrue: function( actual, message ) {
		strictEqual( actual, true, message );
	},

	// Expect boolean false
	assertFalse: function( actual, message ) {
		strictEqual( actual, false, message );
	},

	// Expect numerical value less than X
	lt: function( actual, expected, message ) {
		QUnit.push( actual < expected, actual, 'less than ' + expected, message );
	},

	// Expect numerical value less than or equal to X
	ltOrEq: function( actual, expected, message ) {
		QUnit.push( actual <= expected, actual, 'less than or equal to ' + expected, message );
	},

	// Expect numerical value greater than X
	gt: function( actual, expected, message ) {
		QUnit.push( actual > expected, actual, 'greater than ' + expected, message );
	},

	// Expect numerical value greater than or equal to X
	gtOrEq: function( actual, expected, message ) {
		QUnit.push( actual >= expected, actual, 'greater than or equal to ' + expected, message );
	},

	// Backwards compatible with new verions of QUnit
	equals: window.equal,
	same: window.deepEqual
};

$.extend( QUnit, addons );
$.extend( window, addons );

})( jQuery );
