;( function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "jquery" ], window, factory );
	} else if ( typeof module === "object" && module.exports ) {

		// Node/CommonJS
		// eslint-disable-next-line no-undef
		module.exports = factory( require( "jquery" ), window );
	} else {

		// Browser globals
		factory( jQuery, window );
	}
} )( function( jQuery, window ) {
"use strict";
