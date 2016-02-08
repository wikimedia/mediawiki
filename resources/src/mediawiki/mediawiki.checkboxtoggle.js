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

	var $checkboxes = $( 'li input[type=checkbox]' );

	function selectAll( check ) {
		$checkboxes.prop( 'checked', check );
	}

	$( '.mw-checkbox-all' ).click( function ( e ) {
		selectAll( true );
		e.preventDefault();
	} );
	$( '.mw-checkbox-none' ).click( function ( e ) {
		selectAll( false );
		e.preventDefault();
	} );
	$( '.mw-checkbox-invert' ).click( function ( e ) {
		$checkboxes.each( function () {
			$( this ).prop( 'checked', !$( this ).is( ':checked' ) );
		} );
		e.preventDefault();
	} );

}( mediaWiki, jQuery ) );

