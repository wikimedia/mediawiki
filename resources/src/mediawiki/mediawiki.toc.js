/**
 * @private
 * @singleton
 * @class mw.toc
 */
( function ( mw, $ ) {
	'use strict';

	// Table of contents toggle
	mw.hook( 'wikipage.content' ).add( function ( $content ) {

		/**
		 * Hide/show the table of contents element
		 *
		 * @param {jQuery} $toggleLink A jQuery object of the toggle link.
		 */
		function toggleToc( $toggleLink ) {
			var $tocList = $content.find( '#toc ul:first' );

			// This function shouldn't be called if there's no TOC,
			// but just in case...
			if ( $tocList.length ) {
				if ( $tocList.is( ':hidden' ) ) {
					$tocList.slideDown( 'fast' );
					$toggleLink.text( mw.msg( 'hidetoc' ) );
					$content.find( '#toc' ).removeClass( 'tochidden' );
					$.cookie( 'mw_hidetoc', null, {
						expires: 30,
						path: '/'
					} );
				} else {
					$tocList.slideUp( 'fast' );
					$toggleLink.text( mw.msg( 'showtoc' ) );
					$content.find( '#toc' ).addClass( 'tochidden' );
					$.cookie( 'mw_hidetoc', '1', {
						expires: 30,
						path: '/'
					} );
				}
			}
		}

		var $tocTitle, $tocToggleLink, hideTocCookie;
		$tocTitle = $content.find( '#toctitle' );
		$tocToggleLink = $content.find( '#togglelink' );
		// Only add it if there is a TOC and there is no toggle added already
		if ( $content.find( '#toc' ).length && $tocTitle.length && !$tocToggleLink.length ) {
			hideTocCookie = $.cookie( 'mw_hidetoc' );
			$tocToggleLink = $( '<a href="#" class="internal" id="togglelink"></a>' )
				.text( mw.msg( 'hidetoc' ) )
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

			if ( hideTocCookie === '1' ) {
				toggleToc( $tocToggleLink );
			}
		}
	} );

}( mediaWiki, jQuery ) );
