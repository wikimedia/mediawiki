( function ( mw, $ ) {
	'use strict';

	/**
	 * Fired when the content of the diff displyed on the diff page changes
	 *
	 * @event diffpage_diffchange
	 * @member mw.hook
	 * @param {jQuery} $content
	 */

	$( function () {
		var $diffTable = $( 'table.diff' );
		mw.hook( 'diffpage.diffchange' ).fire( $diffTable );
	} );

}( mediaWiki, jQuery ) );
