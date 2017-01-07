/*!
 * Allows users to perform all / none / invert operations on a list of
 * checkboxes on the page.
 *
 * @licence GNU GPL v2+
 * @author Luke Faraone <luke at faraone dot cc>
 *
 * Based on ext.nuke.js from https://www.mediawiki.org/wiki/Extension:Nuke by
 * Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

( function ( mw, $ ) {
	'use strict';

	$( function () {
		var $checkboxes = $( 'input[type="checkbox"]' );

		function selectAll( check ) {
			$checkboxes.prop( 'checked', check );
		}

		$( '.mw-checkbox-all' ).click( function ( e ) {
			e.preventDefault();
			selectAll( true );
		} );
		$( '.mw-checkbox-none' ).click( function ( e ) {
			e.preventDefault();
			selectAll( false );
		} );
		$( '.mw-checkbox-invert' ).click( function ( e ) {
			e.preventDefault();
			$checkboxes.prop( 'checked', function ( i, val ) {
				return !val;
			} );
		} );

	} );

}( mediaWiki, jQuery ) );
