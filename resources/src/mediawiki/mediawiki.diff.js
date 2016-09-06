( function ( mw, $ ) {
	'use strict';

	$( function () {
		var $diffTable = $( 'table.diff[data-mw="interface"]' );

		/**
		 * Fired when the diff is added to a page containing a diff
		 *
		 * Similar to the {@link mw.hook#event-wikipage_content wikipage.content hook}
		 * $diff may still be detached when the hook is fired.
		 *
		 * @event wikipage_diff
		 * @member mw.hook
		 * @param {jQuery} $diff The root element of the MediaWiki diff (`table.diff`).
		 */
		mw.hook( 'wikipage.diff' ).fire( $diffTable.eq( 0 ) );

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
