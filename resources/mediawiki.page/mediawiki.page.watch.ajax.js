/**
 * Animate watch/unwatch links to use asynchronous API requests to
 * watch pages, rather than navigating to a different URI.
 *
 * @class mw.page.watch.ajax
 */
( function ( mw, $ ) {
	// The name of the page to watch or unwatch
	var title = mw.config.get( 'wgRelevantPageName', mw.config.get( 'wgPageName' ) );

	/**
	 * Update the link text, link href attribute and (if applicable)
	 * "loading" class.
	 *
	 * @param {jQuery} $link Anchor tag of (un)watch link
	 * @param {string} action One of 'watch', 'unwatch'
	 * @param {string} [state="idle"] 'idle' or 'loading'. Default is 'idle'
	 */
	function updateWatchLink( $link, action, state ) {
		var accesskeyTip, msgKey, $li, otherAction;

		// message keys 'watch', 'watching', 'unwatch' or 'unwatching'.
		msgKey = state === 'loading' ? action + 'ing' : action;
		otherAction = action === 'watch' ? 'unwatch' : 'watch';
		accesskeyTip = $link.attr( 'title' ).match( mw.util.tooltipAccessKeyRegexp );
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
			.attr( 'title', mw.msg( 'tooltip-ca-' + action ) +
				( accesskeyTip ? ' ' + accesskeyTip[0] : '' )
			)
			.attr( 'href', mw.util.wikiScript() + '?' + $.param( {
					title: title,
					action: action
				} )
			);

		// Most common ID style
		if ( $li.prop( 'id' ) === 'ca-' + otherAction ) {
			$li.prop( 'id', 'ca-' + action );
		}

		// Special case for vector icon
		if ( $li.hasClass( 'icon' ) ) {
			if ( state === 'loading' ) {
				$link.addClass( 'loading' );
			} else {
				$link.removeClass( 'loading' );
			}
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
		var action, actionPaths, key, i, m, parts;

		actionPaths = mw.config.get( 'wgActionPaths' );

		// TODO: Does MediaWiki give action path or query param
		// precedence ? If the former, move this to the bottom
		action = mw.util.getParamValue( 'action', url );
		if ( action !== null ) {
			return action;
		}

		for ( key in actionPaths ) {
			if ( actionPaths.hasOwnProperty( key ) ) {
				parts = actionPaths[key].split( '$1' );
				for ( i = 0; i < parts.length; i += 1 ) {
					parts[i] = $.escapeRE( parts[i] );
				}
				m = new RegExp( parts.join( '(.+)' ) ).exec( url );
				if ( m && m[1] ) {
					return key;
				}

			}
		}

		return 'view';
	}

	// Expose public methods
	mw.page.watch = {
		updateWatchLink: updateWatchLink
	};

	$( function () {
		var $links = $( '.mw-watchlink a, a.mw-watchlink, ' +
			'#ca-watch a, #ca-unwatch a, #mw-unwatch-link1, ' +
			'#mw-unwatch-link2, #mw-watch-link2, #mw-watch-link1' );

		// Allowing people to add inline animated links is a little scary
		$links = $links.filter( ':not( #bodyContent *, #content * )' );

		$links.click( function ( e ) {
			var action, api, $link;

			// Start preloading the notification module (normally loaded by mw.notify())
			mw.loader.load( ['mediawiki.notification'], null, true );

			action = mwUriGetAction( this.href );

			if ( action !== 'watch' && action !== 'unwatch' ) {
				// Could not extract target action from link url,
				// let native browsing handle it further
				return true;
			}
			e.preventDefault();
			e.stopPropagation();

			$link = $( this );

			updateWatchLink( $link, action, 'loading' );

			api = new mw.Api();
			api[action]( title )
			.done( function ( watchResponse ) {
				var otherAction;

				otherAction = action === 'watch' ? 'unwatch' : 'watch';

				mw.notify( $.parseHTML( watchResponse.message ), {
					tag: 'watch-self'
				} );

				// Set link to opposite
				updateWatchLink( $link, otherAction );

				// Bug 12395 - update the watch checkbox on edit pages when the
				// page is watched or unwatched via the tab.
				if ( watchResponse.watched !== undefined ) {
					$( '#wpWatchthis' ).prop( 'checked', true );
				} else {
					$( '#wpWatchthis' ).prop( 'checked', false );
				}
			} )
			.fail( function () {
				var cleanTitle, msg, link;

				// Reset link to non-loading mode
				updateWatchLink( $link, action );

				// Format error message
				cleanTitle = title.replace( /_/g, ' ' );
				link = mw.html.element(
					'a', {
						href: mw.util.getUrl( title ),
						title: cleanTitle
					}, cleanTitle
				);
				msg = mw.message( 'watcherrortext', link );

				// Report to user about the error
				mw.notify( msg, { tag: 'watch-self' } );
			} );
		} );
	} );

}( mediaWiki, jQuery ) );
