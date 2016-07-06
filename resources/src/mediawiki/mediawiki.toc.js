( function ( mw, $ ) {
	'use strict';

	// Table of contents toggle
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var $toc, $tocTitle, $tocToggleLink, $tocList, hideToc;
		$toc = $content.find( '.toc' ).addBack( '.toc' );

		$toc.each( function () {
			var $this = $( this );
			$tocTitle = $this.find( '.toctitle' );
			$tocToggleLink = $this.find( '.togglelink' );
			$tocList = $this.find( 'ul' ).eq( 0 );

			// Hide/show the table of contents element
			function toggleToc() {
				if ( $tocList.is( ':hidden' ) ) {
					$tocList.slideDown( 'fast' );
					$tocToggleLink.text( mw.msg( 'hidetoc' ) );
					$this.removeClass( 'tochidden' );
					mw.cookie.set( 'hidetoc', null );
				} else {
					$tocList.slideUp( 'fast' );
					$tocToggleLink.text( mw.msg( 'showtoc' ) );
					$this.addClass( 'tochidden' );
					mw.cookie.set( 'hidetoc', '1' );
				}
			}

			// Only add it if there is a complete TOC and it doesn't
			// have a toggle added already
			if ( $tocTitle.length && $tocList.length && !$tocToggleLink.length ) {
				hideToc = mw.cookie.get( 'hidetoc' ) === '1';

				$tocToggleLink = $( '<a href="#" class="togglelink"></a>' )
					.text( hideToc ? mw.msg( 'showtoc' ) : mw.msg( 'hidetoc' ) )
					.click( function ( e ) {
						e.preventDefault();
						toggleToc();
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
					$this.addClass( 'tochidden' );
				}
			}
		} );
	} );

}( mediaWiki, jQuery ) );
