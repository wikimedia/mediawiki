/**
 * Animate watch/unwatch links to use asynchronous API requests to
 * watch pages, rather than clicking on links. Requires jQuery.
 * Uses jsMsg() from wikibits.js.
 */

if ( typeof wgAjaxWatch === 'undefined' || !wgAjaxWatch ) {
	window.wgAjaxWatch = { };
}

wgAjaxWatch.setLinkText = function( $link, action ) {
	if ( action == 'watch' || action == 'unwatch' ) {
		// save the accesskey from the title
		var keyCommand = $link.attr( 'title' ).match( /\[.*?\]$/ ) ? $link.attr( 'title' ).match( /\[.*?\]$/ )[0] : '';
		$link.attr( 'title', mediaWiki.msg( 'tooltip-ca-' + action ) + ' ' + keyCommand );
	}
	if ( $link.data( 'icon' ) ) {
		$link.attr( 'alt', mediaWiki.msg( action ) );
		if ( action == 'watching' || action == 'unwatching' ) {
			$link.addClass( 'loading' );
		} else {
			$link.removeClass( 'loading' );
		}
	} else {
		$link.html( mediaWiki.msg( action ) );
	}
};

wgAjaxWatch.processResult = function( response ) {
	response = response.watch;
	var $link = $( this );
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
		$( '#wpWatchthis' ).attr( 'checked', '1' );
	} else {
		$( '#wpWatchthis' ).removeAttr( 'checked' );
	}
};

$( document ).ready( function() {
	var $links = $( '.mw-watchlink a, a.mw-watchlink' );
	// BC with older skins
	$links = $links
		.add( $( '#ca-watch a, #ca-unwatch a, a#mw-unwatch-link1' ) )
		.add( $( 'a#mw-unwatch-link2, a#mw-watch-link2, a#mw-watch-link1' ) );
	// allowing people to add inline animated links is a little scary
	$links = $links.filter( ':not( #bodyContent *, #content * )' );

	$links.each( function() {
		var $link = $( this );
		$link
			.data( 'icon', $link.parents( 'li' ).hasClass( 'icon' ) )
			.data( 'action', $link.attr( 'href' ).match( /[\?&]action=unwatch/i ) ? 'unwatch' : 'watch' );
		var title = $link.attr( 'href' ).match( /[\?&]title=(.*?)&/i )[1];
		$link.data( 'target', decodeURIComponent( title ).replace( /_/g, ' ' ) );
	});

	$links.click( function( event ) {
		var $link = $( this );

		if( wgAjaxWatch.supported === false || !wgEnableWriteAPI || !wfSupportsAjax() ) {
			// Lazy initialization so we don't toss up
			// ActiveX warnings on initial page load
			// for IE 6 users with security settings.
			wgAjaxWatch.$links.unbind( 'click' );
			return true;
		}

		wgAjaxWatch.setLinkText( $link, $link.data( 'action' ) + 'ing' );
		$.get( wgScriptPath
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
		var $link = $( this );
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
				$link.text( mediaWiki.msg( otheraction ) );
			}
		};
		return false;
	});

	wgAjaxWatch.$links = $links;
});
