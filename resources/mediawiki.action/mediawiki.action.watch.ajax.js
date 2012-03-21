/**
 * Animate watch/unwatch links to use asynchronous API requests to
 * watch pages, rather than navigating to a different URI.
 */
( function ( $, mw, undefined ) {

/**
 * The name of the page to watch or unwatch.
 */
var title = mw.config.get( 'wgRelevantPageName', mw.config.get( 'wgPageName' ) );

/**
 * Update the link text, link href attribute and (if applicable)
 * "loading" class.
 *
 * @param $link {jQuery} Anchor tag of (un)watch link
 * @param action {String} One of 'watch', 'unwatch'.
 * @param state {String} [optional] 'idle' or 'loading'. Default is 'idle'.
 */
function updateWatchLink( $link, action, state ) {
	// message keys 'watch', 'watching', 'unwatch' or 'unwatching'.
	var	msgKey = state === 'loading' ? action + 'ing' : action,
		accesskeyTip = $link.attr( 'title' ).match( mw.util.tooltipAccessKeyRegexp ),
		$li = $link.closest( 'li' );

	$link
		.text( mw.msg( msgKey ) )
		.attr( 'title', mw.msg( 'tooltip-ca-' + action ) +
			( accesskeyTip ? ' ' + accesskeyTip[0] : '' )
		)
		.attr( 'href', mw.util.wikiScript() + '?' + $.param({
				title: title,
				action: action
			})
		);

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
 * @todo This should be moved somewhere more accessible.
 * @param url {String}
 * @return {String} The extracted action, defaults to 'view'.
 */
function mwUriGetAction( url ) {
	var	actionPaths = mw.config.get( 'wgActionPaths' ),
		key, parts, m, action;

	// @todo: Does MediaWiki give action path or query param
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

$( document ).ready( function() {
	var $links = $( '.mw-watchlink a, a.mw-watchlink, ' +
		'#ca-watch a, #ca-unwatch a, #mw-unwatch-link1, ' +
		'#mw-unwatch-link2, #mw-watch-link2, #mw-watch-link1' );

	// Allowing people to add inline animated links is a little scary
	$links = $links.filter( ':not( #bodyContent *, #content * )' );

	$links.click( function( e ) {
		var	$link, api,
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
		api[action](
			title,
			// Success
			function( watchResponse ) {
				var	otherAction = action === 'watch' ? 'unwatch' : 'watch',
					$li = $link.closest( 'li' );

				mw.util.jsMessage( watchResponse.message, 'ajaxwatch' );

				// Set link to opposite
				updateWatchLink( $link, otherAction );

				// Most common ID style
				if ( $li.prop( 'id' ) === 'ca-' + otherAction || $li.prop( 'id' ) === 'ca-' + action ) {
					$li.prop( 'id', 'ca-' + otherAction );
				}
				
				// Bug 12395 - update the watch checkbox on edit pages when the
				// page is watched or unwatched via the tab.
				if ( watchResponse.watched !== undefined ) {
					$( '#wpWatchthis' ).prop( 'checked', true );
				} else {
					$( '#wpWatchthis' ).removeProp( 'checked' );
				}
			},
			// Error
			function(){		

				// Reset link to non-loading mode
				updateWatchLink( $link, action );
				
				// Format error message
				var cleanTitle = title.replace( /_/g, ' ' );
				var link = mw.html.element(
					'a', {
						'href': mw.util.wikiGetlink( title ),
						'title': cleanTitle
					}, cleanTitle
				);
				var html = mw.msg( 'watcherrortext', link );
				
				// Report to user about the error
				mw.util.jsMessage( html, 'ajaxwatch' );

			}
		);
	});

});

})( jQuery, mediaWiki );
