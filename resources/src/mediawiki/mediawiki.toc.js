/**
 * @private
 * @singleton
 * @class mw.toc
 */
( function ( mw, $ ) {
	'use strict';

	// Table of contents toggle
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var $toc, $tocTitle, $tocToggleLink, hideToc;
		$toc = $content.find( '#toc' );
		$tocTitle = $content.find( '#toctitle' );
		$tocToggleLink = $content.find( '#togglelink' );

		/**
		 * Hide/show the table of contents element
		 *
		 * @param {jQuery} $toggleLink A jQuery object of the toggle link.
		 */
		function toggleToc( $toggleLink ) {
			var $tocList = $toc.find( 'ul:first' );

			// This function shouldn't be called if there's no TOC,
			// but just in case...
			if ( $tocList.length ) {
				if ( $tocList.is( ':hidden' ) ) {
					$tocList.slideDown( 'fast' );
					$toggleLink.text( mw.msg( 'hidetoc' ) );
					$toc.removeClass( 'tochidden' );
					$.cookie( 'mw_hidetoc', null, {
						expires: 30,
						path: '/'
					} );
				} else {
					$tocList.slideUp( 'fast' );
					$toggleLink.text( mw.msg( 'showtoc' ) );
					$toc.addClass( 'tochidden' );
					$.cookie( 'mw_hidetoc', '1', {
						expires: 30,
						path: '/'
					} );
				}
			}
		}
		// Only add it if there is a TOC and there is no toggle added already
		if ( $toc.length && $tocTitle.length && !$tocToggleLink.length ) {
			hideToc = $.cookie( 'mw_hidetoc' ) === '1';
			$tocToggleLink = $( '<a href="#" class="internal" id="togglelink"></a>' )
				.text( hideToc ? mw.msg( 'showtoc' ) : mw.msg( 'hidetoc' ) )
				.click( function ( e ) {
					e.preventDefault();
					toggleToc( $( this ) );
				} );
			$tocTitle.append(
				$tocToggleLink
					.wrap( '<span class="toctoggle"></span>' )
					.parent()
						.prepend( '&nbsp;[' )
						.append( ']&nbsp;' )
			);

			if ( hideToc ) {
				$toc.find( 'ul:first' ).hide();
				$toc.addClass( 'tochidden' );
			}
		}
	} );

}( mediaWiki, jQuery ) );
