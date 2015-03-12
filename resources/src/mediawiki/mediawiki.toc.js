( function ( mw, $ ) {
	'use strict';

	// Table of contents toggle
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var $tocTitle = $content.find( '#toctitle' ),
			$toggles = $tocTitle.find( 'a' );

		if ( $tocTitle.length === 0 ) {
			return;
		}

		if ( $toggles.length === 0 ) {
			// Temporary hack until the change to generate .toctoggle in PHP (Ic865bf67c) propagates.
			$tocTitle.append(
				'<span class="toctoggle">&nbsp;[' +
					'<a href="#" class="toc-show">' + mw.message( 'showtoc' ).escaped() + '</a>' +
					'<a href="#" class="toc-hide">' + mw.message( 'hidetoc' ).escaped() + '</a>' +
				']&nbsp;</span>'
			);
			$toggles = $tocTitle.find( 'a' );
		}

		$toggles.on( 'click', function ( e ) {
			e.preventDefault();
			var hide = $( 'html' ).toggleClass( 'hide-toc' ).hasClass( 'hide-toc' );
			$.cookie( 'mw_hidetoc', hide ? '1' : null, { expires: 30, path: '/' } );
		} );
	} );

}( mediaWiki, jQuery ) );
