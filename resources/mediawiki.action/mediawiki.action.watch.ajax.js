/**
 * Animate watch/unwatch links to use asynchronous API requests to
 * watch pages, rather than clicking on links. Requires jQuery.
 */
( function( $ ) {
var $links;

var setLinkText = function( $link, action ) {
	if ( action == 'watch' || action == 'unwatch' ) {
		// save the accesskey from the title
		var keyCommand = $link.attr( 'title' ).match( /\[.*?\]$/ ) ? $link.attr( 'title' ).match( /\[.*?\]$/ )[0] : '';
		$link.attr( 'title', mw.msg( 'tooltip-ca-' + action ) + ' ' + keyCommand );
	}
	if ( $link.data( 'icon' ) ) {
		$link.attr( 'alt', mw.msg( action ) );
		if ( action == 'watching' || action == 'unwatching' ) {
			$link.addClass( 'loading' );
		} else {
			$link.removeClass( 'loading' );
		}
	} else {
		$link.html( mw.msg( action ) );
	}
};

var processResult = function( response, $link ) {
	watchResponse = response.watch;

	// To ensure we set the same status for all watch links with the
	// same target we trigger a custom event on *all* watch links.
	if ( watchResponse.watched !== undefined ) {
		$links.trigger( 'mw-ajaxwatch', [watchResponse.title, 'watch', $link] );
	} else if ( watchResponse.unwatched !== undefined ) {
		$links.trigger( 'mw-ajaxwatch', [watchResponse.title, 'unwatch', $link] );
	} else {
		// Either we got an error code or it just plain broke.
		window.location.href = $link[0].href;
		return;
	}

	mw.util.jsMessage( watchResponse.message, 'watch' );

	// Bug 12395 - update the watch checkbox on edit pages when the
	// page is watched or unwatched via the tab.
	if ( watchResponse.watched !== undefined ) {
		$( '#wpWatchthis' ).attr( 'checked', 'checked' );
	} else {
		$( '#wpWatchthis' ).removeAttr( 'checked' );
	}
};

$( document ).ready( function() {
	$links = $( '.mw-watchlink a, a.mw-watchlink' );
	// BC with older skins
	$links = $links
		.add( '#ca-watch a, #ca-unwatch a, a#mw-unwatch-link1, ' +
			'a#mw-unwatch-link2, a#mw-watch-link2, a#mw-watch-link1' );
	// allowing people to add inline animated links is a little scary
	$links = $links.filter( ':not( #bodyContent *, #content * )' );

	$links.each( function() {
		var $link = $( this );
		var link = this;
		$link
			.data( 'icon', $link.closest( 'li' ).hasClass( 'icon' ) )
			.data( 'action', mw.util.getActionFrom( link.href ) == 'unwatch' ? 'unwatch' : 'watch' );
		var title = mw.util.getTitleFrom( link.href );
		$link.data( 'target', title.replace( /_/g, ' ' ) );
	});

	$links.click( function( event ) {
		var $link = $( this );

		if ( !mw.config.get( 'wgEnableWriteAPI' ) ) {
			// Lazy initialization so we don't toss up
			// ActiveX warnings on initial page load
			// for IE 6 users with security settings.
			$links.unbind( 'click' );
			return true;
		}

		setLinkText( $link, $link.data( 'action' ) + 'ing' );

		var reqData = {
			'action': 'watch',
			'format': 'json',
			'title': $link.data( 'target' ),
			// API return contains a localized data.watch.message string.
			'uselang': mw.config.get( 'wgUserLanguage' )
		};
		if ( $link.data( 'action' ) == 'unwatch' ) {
			reqData.unwatch = '';
		}
		$.getJSON( mw.util.wikiScript( 'api' ),
			reqData,
			function( data, textStatus, xhr ) {
				processResult( data, $link );
			}
		);

		return false;
	});

	// When a request returns, a custom event 'mw-ajaxwatch' is triggered
	// on *all* watch links, so they can be updated if necessary
	$links.bind( 'mw-ajaxwatch', function( event, target, action, $link ) {
		var foo = $link.data( 'target' );
		if ( $link.data( 'target' ) == target ) {
			var otheraction = action == 'watch'
				? 'unwatch'
				: 'watch';

			$link.data( 'action', otheraction );
			setLinkText( $link, otheraction );
			$link.attr( 'href',
				mw.config.get( 'wgScript' )
				+ '?title=' + mw.util.wikiUrlencode( mw.config.get( 'wgPageName' ) )
				+ '&action=' + otheraction
			);
			if ( $link.closest( 'li' ).attr( 'id' ) == 'ca-' + action ) {
				$link.closest( 'li' ).attr( 'id', 'ca-' + otheraction );
				// update the link text with the new message
				$link.text( mw.msg( otheraction ) );
			}
		}

		return false;
	});

});

})( jQuery );
