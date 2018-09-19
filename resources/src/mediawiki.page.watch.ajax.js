/**
 * Animate watch/unwatch links to use asynchronous API requests to
 * watch pages, rather than navigating to a different URI.
 *
 * Usage:
 *
 *     var watch = require( 'mediawiki.page.watch.ajax' );
 *     watch.updateWatchLink(
 *         $node,
 *         'watch',
 *         'loading'
 *     );
 *
 * @class mw.plugin.page.watch.ajax
 * @singleton
 */
( function () {
	var watch,
		// The name of the page to watch or unwatch
		title = mw.config.get( 'wgRelevantPageName' );

	/**
	 * Update the link text, link href attribute and (if applicable)
	 * "loading" class.
	 *
	 * @param {jQuery} $link Anchor tag of (un)watch link
	 * @param {string} action One of 'watch', 'unwatch'
	 * @param {string} [state="idle"] 'idle' or 'loading'. Default is 'idle'
	 */
	function updateWatchLink( $link, action, state ) {
		var msgKey, $li, otherAction;

		// A valid but empty jQuery object shouldn't throw a TypeError
		if ( !$link.length ) {
			return;
		}

		// Invalid actions shouldn't silently turn the page in an unrecoverable state
		if ( action !== 'watch' && action !== 'unwatch' ) {
			throw new Error( 'Invalid action' );
		}

		// message keys 'watch', 'watching', 'unwatch' or 'unwatching'.
		msgKey = state === 'loading' ? action + 'ing' : action;
		otherAction = action === 'watch' ? 'unwatch' : 'watch';
		$li = $link.closest( 'li' );

		// Trigger a 'watchpage' event for this List item.
		// Announce the otherAction value as the first param.
		// Used to monitor the state of watch link.
		// TODO: Revise when system wide hooks are implemented
		if ( state === undefined ) {
			$li.trigger( 'watchpage.mw', otherAction );
		}

		$link
			.text( mw.msg( msgKey ) )
			.attr( 'title', mw.msg( 'tooltip-ca-' + action ) )
			.updateTooltipAccessKeys()
			.attr( 'href', mw.util.getUrl( title, { action: action } ) );

		// Most common ID style
		if ( $li.prop( 'id' ) === 'ca-' + otherAction ) {
			$li.prop( 'id', 'ca-' + action );
		}

		if ( state === 'loading' ) {
			$link.addClass( 'loading' );
		} else {
			$link.removeClass( 'loading' );
		}
	}

	/**
	 * TODO: This should be moved somewhere more accessible.
	 *
	 * @private
	 * @param {string} url
	 * @return {string} The extracted action, defaults to 'view'
	 */
	function mwUriGetAction( url ) {
		var action, actionPaths, key, m, parts;

		// TODO: Does MediaWiki give action path or query param
		// precedence? If the former, move this to the bottom
		action = mw.util.getParamValue( 'action', url );
		if ( action !== null ) {
			return action;
		}

		actionPaths = mw.config.get( 'wgActionPaths' );
		for ( key in actionPaths ) {
			parts = actionPaths[ key ].split( '$1' );
			parts = parts.map( mw.RegExp.escape );
			m = new RegExp( parts.join( '(.+)' ) ).exec( url );
			if ( m && m[ 1 ] ) {
				return key;
			}
		}

		return 'view';
	}

	// Expose public methods
	watch = {
		updateWatchLink: updateWatchLink
	};
	module.exports = watch;

	$( function () {
		var $links = $( '.mw-watchlink a[data-mw="interface"], a.mw-watchlink[data-mw="interface"]' );
		if ( !$links.length ) {
			// Fallback to the class-based exclusion method for backwards-compatibility
			$links = $( '.mw-watchlink a, a.mw-watchlink' );
			// Restrict to core interfaces, ignore user-generated content
			$links = $links.filter( ':not( #bodyContent *, #content * )' );
		}

		$links.click( function ( e ) {
			var mwTitle, action, api, $link;

			mwTitle = mw.Title.newFromText( title );
			action = mwUriGetAction( this.href );

			if ( !mwTitle || ( action !== 'watch' && action !== 'unwatch' ) ) {
				// Let native browsing handle the link
				return true;
			}
			e.preventDefault();
			e.stopPropagation();

			$link = $( this );

			if ( $link.hasClass( 'loading' ) ) {
				return;
			}

			updateWatchLink( $link, action, 'loading' );

			// Preload the notification module for mw.notify
			mw.loader.load( 'mediawiki.notification' );

			api = new mw.Api();

			api[ action ]( title )
				.done( function ( watchResponse ) {
					var message, otherAction = action === 'watch' ? 'unwatch' : 'watch';

					if ( mwTitle.isTalkPage() ) {
						message = action === 'watch' ? 'addedwatchtext-talk' : 'removedwatchtext-talk';
					} else {
						message = action === 'watch' ? 'addedwatchtext' : 'removedwatchtext';
					}

					mw.notify( mw.message( message, mwTitle.getPrefixedText() ).parseDom(), {
						tag: 'watch-self'
					} );

					// Set link to opposite
					updateWatchLink( $link, otherAction );

					// Update the "Watch this page" checkbox on action=edit when the
					// page is watched or unwatched via the tab (T14395).
					$( '#wpWatchthis' ).prop( 'checked', watchResponse.watched === true );
				} )
				.fail( function () {
					var msg, link;

					// Reset link to non-loading mode
					updateWatchLink( $link, action );

					// Format error message
					link = mw.html.element(
						'a', {
							href: mw.util.getUrl( title ),
							title: mwTitle.getPrefixedText()
						}, mwTitle.getPrefixedText()
					);
					msg = mw.message( 'watcherrortext', link );

					// Report to user about the error
					mw.notify( msg, {
						tag: 'watch-self',
						type: 'error'
					} );
				} );
		} );
	} );

}() );
