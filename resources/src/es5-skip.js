/*!
 * Skip function for es5-shim module.
 *
 * Test for strict mode as a proxy for full ES5 function support (but not syntax)
 * Per http://kangax.github.io/compat-table/es5/ this is a reasonable shortcut
 * that still allows this to be as short as possible (there are no browsers we
 * support that have strict mode, but lack other features).
 *
 * Do explicitly test for Function#bind because of PhantomJS (which implements
 * strict mode, but lacks Function#bind).
 *
 * IE9 supports all features except strict mode, so loading es5-shim should be close to
 * a no-op but does increase page payload).
 */
return ( function () {
	'use strict';
	return !this && !!Function.prototype.bind;
}() );
