/*!
 * MediaWiki ES5 feature detection shim skip function.
 *
 * Test for strict mode as a proxy for full ES5 function support (but not syntax)
 * Per http://kangax.github.io/compat-table/es5/ this is a reasonable short-cut
 * that still allows this to be as short as possible (there are no function "No"s
 * for non-"obsolete" real browsers where strict support is available).
 *
 * Note that this will cause IE9 users to get the shim (which should be close to
 * a no-op but will increase page payload).
 */

return ( function () { "use strict"; return !this; }() );
