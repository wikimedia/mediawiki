/*!
 * Scripts for diff pages at ready
 */
( function ( mw, $ ) {
	'use strict';

	/**
	 * Fired when the diff is added to a page containing a diff
	 *
	 * Similar to the {@link mw.hook#event-wikipage_content wikipage.content hook}
	 * $diff can still be detached when this hook is fired.
	 *
	 * @event wikipage_diff
	 * @member mw.hook
	 * @param {jQuery} $diff The root element of the MediaWiki diff 
	 *  which should be table.diff.
	 */

	$( function () {
		mw.hook( 'wikipage.diff' ).fire( $( '.diff' ) );
	} );
}( mediaWiki, jQuery ) );
