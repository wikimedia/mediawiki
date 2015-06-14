/*!
 * Scripts for diff pages at ready
 */
( function ( mw, $ ) {
	$( function () {
		var $diff = $( 'table.diff' );
		if ( $diff.length ) {
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
			mw.hook( 'wikipage.diff' ).fire( $diff.eq( 0 ) );
		}
	} );
}( mediaWiki, jQuery ) );
