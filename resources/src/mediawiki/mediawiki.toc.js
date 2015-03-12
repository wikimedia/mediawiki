( function ( mw, $ ) {
	'use strict';

	// Table of contents toggle
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var $tocTitle = $( '#toctitle' ), $toggles = $tocTitle.find( 'a' );

		if ( $tocTitle.length === 0 ) {
			return;
		}

		if ( $toggles.length === 0 ) {
			// Temporary hack until the change to generate .toctoggle in PHP propagates.
			$tocTitle.append(
				'<span class="toctoggle">&nbsp;[' +
					'<a href="#" class="toctoggle-show">' + mw.message( 'showtoc' ).text() + '</a>' +
					'<a href="#" class="toctoggle-hide">' + mw.message( 'hidetoc' ).text() + '</a>' +
				']&nbsp;</span>'
			);
			$toggles = $tocTitle.find( 'a' );
		}

		$toggles.on( 'click', function ( e ) {
			var cookieVal = $( 'html' ).hasClass( 'hide-toc' ) ? null : '1';
			$( 'html' ).toggleClass( 'hide-toc', !!cookieVal );
			$.cookie( 'mw_hidetoc', cookieVal, { expires: 30, path: '/' } );
			e.preventDefault();
		} );
	} );

}( mediaWiki, jQuery ) );
