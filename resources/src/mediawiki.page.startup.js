( function () {
	// Break out of framesets
	if ( mw.config.get( 'wgBreakFrames' ) ) {
		// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
		// it works only comparing to window.self or window.window (http://stackoverflow.com/q/4850978/319266)
		if ( window.top !== window.self ) {
			// Un-trap us from framesets
			window.top.location.href = location.href;
		}
	}

	$( function () {
		var $diff;

		/**
		 * Fired for wiki content that is or will be rendered somewhere on the page.
		 *
		 * Use this hook if you need to enhance or modify parts of a wiki page.
		 *
		 * This hook should be used instead of `$.ready` as otherwise you will
		 * miss content that is rendered without a browser reload. For example,
		 * during a JavaScript-based live preview, or after saving an edit with
		 * VisualEditor.
		 *
		 * Keep in mind:
		 * - The `$content` might represent content that is not yet publicly saved,
		 *   such as during a (personal) preview.
		 * - The `$content` might represent only a small portion of the page,
		 *   for example when editing a section, or after an extension inserts
		 *   or lazy-loads additional content.
		 * - The `$content` element might be detached (invisible) when the hook runs.
		 *   This has the benefit of allowing you to modify the content beforehand,
		 *   which avoids "flash" or other visual disruption afterwards.
		 *
		 * @event wikipage_content
		 * @member mw.hook
		 * @param {jQuery} $content An element containing wiki content.
		 */
		mw.hook( 'wikipage.content' ).fire( $( '#mw-content-text' ) );

		$diff = $( 'table.diff[data-mw="interface"]' );
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

}() );
