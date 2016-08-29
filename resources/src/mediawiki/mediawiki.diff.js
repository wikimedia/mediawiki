( function ( mw, $ ) {
	'use strict';

	/**
	 * Fired when the content of the diff displayed on the diff page changes
	 *
	 * @event diffpage_diffchange
	 * @member mw.hook
	 * @param {jQuery} $content
	 */

	$( function () {
		var $diffTable = $( 'table.diff[data-mw="interface"]' );
		mw.hook( 'diffpage.diffchange' ).fire( $diffTable );
	} );

}( mediaWiki, jQuery ) );
