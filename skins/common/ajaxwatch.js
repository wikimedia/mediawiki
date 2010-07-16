/**
 * Animate watch/unwatch links to use asynchronous API requests to
 * watch pages, rather than clicking on links. Requires jQuery.
 * Uses jsMsg() from wikibits.js.
 */

if( typeof wgAjaxWatch === 'undefined' || !wgAjaxWatch ) {
	var wgAjaxWatch = {
		watchMsg: 'Watch',
		unwatchMsg: 'Unwatch',
		watchingMsg: 'Watching...',
		unwatchingMsg: 'Unwatching...',
		'tooltip-ca-watchMsg': 'Add this page to your watchlist',
		'tooltip-ca-unwatchMsg': 'Remove this page from your watchlist'
	};
}

wgAjaxWatch.setLinkText = function( $link, action ) {
	if( action == 'watch' || action == 'unwatch' ) {
		// save the accesskey from the title
		var keyCommand = $link.attr( 'title' ).match( /\[.*?\]$/ )
			? $link.attr( 'title' ).match( /\[.*?\]$/ )[0]
			: '';
		$link.attr( 'title', wgAjaxWatch['tooltip-ca-' + action + 'Msg'] + ' ' + keyCommand );
	}
	if( $link.data( 'icon' ) ) {
		$link.attr( 'alt', wgAjaxWatch[action + 'Msg'] );
		if ( action == 'watching' || action == 'unwatching' ) {
			$link.addClass( 'loading' );
		} else {
			$link.removeClass( 'loading' );
		}
	} else {
		$link.html( wgAjaxWatch[action + 'Msg'] );
	}
};

wgAjaxWatch.processResult = function( response ) {
	response = response.watch;
	var $link = $j( this );
	// To ensure we set the same status for all watch links with the
	// same target we trigger a custom event on *all* watch links.
	if( response.watched !== undefined ) {
		wgAjaxWatch.$links.trigger( 'mw-ajaxwatch', [response.title, 'watch'] );
	} else if ( response.unwatched !== undefined ) {
		wgAjaxWatch.$links.trigger( 'mw-ajaxwatch', [response.title, 'unwatch'] );
	} else {
		// Either we got an error code or it just plain broke.
		window.location.href = $link.attr( 'href' );
		return;
	}

	jsMsg( response.message, 'watch' );

	// Bug 12395 - update the watch checkbox on edit pages when the
	// page is watched or unwatched via the tab.
	if( response.watched !== undefined ) {
		$j( '#wpWatchthis' ).attr( 'checked', '1' );
	} else {
		$j( '#wpWatchthis' ).removeAttr( 'checked' );
	}
};

$j( document ).ready( function() {
	var $links = $j( '.mw-watchlink a, a.mw-watchlink' );
	// BC with older skins
	$links = $links
		.add( $j( '#ca-watch a, #ca-unwatch a, a#mw-unwatch-link1' ) )
		.add( $j( 'a#mw-unwatch-link2, a#mw-watch-link2, a#mw-watch-link1' ) );
	// allowing people to add inline animated links is a little scary
	$links = $links.filter( ':not( #bodyContent *, #content * )' );

	$links.each( function() {
		var $link = $j( this );
		$link
			.data( 'icon', $link.parents( 'li' ).hasClass( 'icon' ) )
			.data( 'action', $link.attr( 'href' ).match( /[\?\&]action=unwatch/i ) ? 'unwatch' : 'watch' );
		var title = $link.attr( 'href' ).match( /[\?\&]title=(.*?)&/i )[1];
		$link.data( 'target', decodeURIComponent( title ).replace( /_/g, ' ' ) );
	});

	$links.click( function( event ) {
		var $link = $j( this );

		if( wgAjaxWatch.supported === false || !wgEnableWriteAPI || !wfSupportsAjax() ) {
			// Lazy initialization so we don't toss up
			// ActiveX warnings on initial page load
			// for IE 6 users with security settings.
			wgAjaxWatch.$links.unbind( 'click' );
			return true;
		}

		wgAjaxWatch.setLinkText( $link, $link.data( 'action' ) + 'ing' );
		$j.get( wgScriptPath
				+ '/api' + wgScriptExtension + '?action=watch&format=json&title='
				+ encodeURIComponent( $link.data( 'target' ) )
				+ ( $link.data( 'action' ) == 'unwatch' ? '&unwatch' : '' ),
			{},
			wgAjaxWatch.processResult,
			'json'
		);

		return false;
	});

	// When a request returns, a custom event 'mw-ajaxwatch' is triggered
	// on *all* watch links, so they can be updated if necessary
	$links.bind( 'mw-ajaxwatch', function( event, target, action ) {
		var $link = $j( this );
		var foo = $link.data( 'target' );
		if( $link.data( 'target' ) == target ) {
			var otheraction = action == 'watch'
				? 'unwatch'
				: 'watch';

			$link.data( 'action', otheraction );
			wgAjaxWatch.setLinkText( $link, otheraction );
			$link.attr( 'href', $link.attr( 'href' ).replace( '/&action=' + action + '/', '&action=' + otheraction ) );
			if( $link.parents( 'li' ).attr( 'id' ) == 'ca-' + action ) {
				$link.parents( 'li' ).attr( 'id', 'ca-' + otheraction );
				// update the link text with the new message
				$link.text( wgAjaxWatch[ otheraction + 'Msg'] );
			}
		};
		return false;
	});

	wgAjaxWatch.$links = $links;
});
