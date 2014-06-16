( function ( mw, $ ) {
	'use strict';

	// Table of contents toggle
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var $toc, $tocTitle, $tocToggleLink, $tocList, hideToc;
		$toc = $content.find( '#toc' );
		$tocTitle = $content.find( '#toctitle' );
		$tocToggleLink = $content.find( '#togglelink' );
		$tocList = $toc.find( 'ul' ).eq( 0 );

		// Hide/show the table of contents element
		function toggleToc() {
			if ( $tocList.is( ':hidden' ) ) {
				$tocList.slideDown( 'fast' );
				$tocToggleLink.text( mw.msg( 'hidetoc' ) );
				$toc.removeClass( 'tochidden' );
				mw.cookie.set( 'hidetoc', null );
			} else {
				$tocList.slideUp( 'fast' );
				$tocToggleLink.text( mw.msg( 'showtoc' ) );
				$toc.addClass( 'tochidden' );
				mw.cookie.set( 'hidetoc', '1' );
			}
		}

		// Only add it if there is a complete TOC and it doesn't
		// have a toggle added already
		if ( $toc.length && $tocTitle.length && $tocList.length && !$tocToggleLink.length ) {
			hideToc = mw.cookie.get( 'hidetoc' ) === '1';

			$tocToggleLink = $( '<a role="button" tabindex="0" id="togglelink"></a>' )
				.text( mw.msg( hideToc ? 'showtoc' : 'hidetoc' ) )
				.on( 'click keypress', function ( e ) {
					if (
						e.type === 'click' ||
						e.type === 'keypress' && e.which === 13
					) {
						toggleToc();
					}
				} );

			$tocTitle.append(
				$tocToggleLink
					.wrap( '<span class="toctoggle"></span>' )
					.parent()
						.prepend( '&nbsp;[' )
						.append( ']&nbsp;' )
			);

			if ( hideToc ) {
				$tocList.hide();
				$toc.addClass( 'tochidden' );
			}
		}
	} );

}( mediaWiki, jQuery ) );
