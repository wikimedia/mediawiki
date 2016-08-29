( function ( mw, $ ) {
	'use strict';

	$( function () {
		var $diffTable = $( 'table.diff[data-mw="interface"]' );
		/**
		 * Fired when the content of the diff displayed on the diff page changes
		 *
		 * @event diffpage_diffchange
		 * @member mw.hook
		 * @param {jQuery} $content
		 */
		mw.hook( 'diffpage.diffchange' ).fire( $diffTable );
	} );

}( mediaWiki, jQuery ) );
