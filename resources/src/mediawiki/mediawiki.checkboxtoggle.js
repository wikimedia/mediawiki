/*!
 * Adds a set of toggle links to an element with the class
 * `toggle-option-placeholder`, and allows users to perform all / none / invert
 * operations on a list of checkboxes on the page.
 *
 * @licence GNU GPL v2+
 * @author Luke Faraone <luke at faraone dot cc>
 *
 * Based on ext.nuke.js from https://www.mediawiki.org/wiki/Extension:Nuke by
 * Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

( function ( mw, $ ) {
	'use strict';

	var $checkboxes, $toggleOptions;

	function selectAll( check ) {
		$checkboxes.prop( 'checked', check );
	}

	$checkboxes = $( 'li input[type=checkbox]' );

	$toggleOptions = mw.message( 'checkbox-select', [
		$( '<a>' )
			.text( mw.msg( 'checkbox-all' ) )
			.click( function ( e ) {
				selectAll( true );
				e.preventDefault();
			} ),
		mw.msg( 'comma-separator' ),
		$( '<a>' )
			.text( mw.msg( 'checkbox-none' ) )
			.click( function ( e ) {
				selectAll( false );
				e.preventDefault();
			} ),
		mw.msg( 'comma-separator' ),
		$( '<a>' )
			.text( mw.msg( 'checkbox-invert' ) )
			.click( function ( e ) {
				$checkboxes.each( function () {
					$( this ).prop( 'checked', !$( this ).is( ':checked' ) );
				} );
				e.preventDefault();
			} )
	] ).parseDom();

	$( '.toggle-option-placeholder' ).append( $toggleOptions );

}( mediaWiki, jQuery ) );

