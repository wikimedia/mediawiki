/*!
 * MediaWiki ES5 feature detection shim skip function.
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

var es5Support = false;

try {
	es5Support = !!(
		// Strict mode
		// Commented out so that IE9 doesn't trigger getting the shim
		// ( function () { 'use strict'; return !this; } ) &&

		// Array
		Array.isArray &&
		Array.prototype.every &&
		Array.prototype.filter &&
		Array.prototype.forEach &&
		Array.prototype.indexOf &&
		Array.prototype.lastIndexOf &&
		Array.prototype.map &&
		Array.prototype.some &&
		Array.prototype.reduce &&
		Array.prototype.reduceRight &&

		// Date
		Date.now &&
		Date.prototype.toISOString &&
		Date.prototype.toJSON &&
		Date.parse( '2000-01-02T03:04:56.789Z' ) &&

		// Function
		Function.prototype.bind &&

		// Number
		Number.prototype.toFixed &&

		// Object
		Object.create &&
		Object.defineProperties &&
		Object.defineProperty &&
		Object.freeze &&
		Object.getOwnPropertyDescriptor &&
		Object.getOwnPropertyNames &&
		Object.getPrototypeOf &&
		Object.isExtensible &&
		Object.isFrozen &&
		Object.isSealed &&
		Object.keys &&
		Object.preventExtensions &&
		Object.seal &&

		// String
		String.prototype.replace &&
		String.prototype.split &&
		String.prototype.trim &&

		true
	);
} catch (e) {
	return false;
}

return es5Support;
