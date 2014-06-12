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
 *
 * @copyright 2014 James Forrester, Timo Tijhof and others
 * @license The MIT License (MIT)
 *
 * Includes code taken from the VisualEditor ES5 feature detection code used under the MIT license
 * https://git.wikimedia.org/summary/mediawiki%2Fextensions%2FVisualEditor.git
 *
 * Includes code taken from the Modernizr es5 feature detection code used under the MIT license
 * https://github.com/Modernizr/Modernizr/tree/master/feature-detects/es5
 */

return !!( ( function () { 'use strict'; return !this; } ) );
