( function () {
	'use strict';

	function initToc( tocNode ) {
		var hidden = false,
			toggleNode = tocNode.querySelector( '.toctogglecheckbox' );

		if ( !toggleNode ) {
			return;
		}

		toggleNode.addEventListener( 'change', function () {
			hidden = !hidden;
			mw.cookie.set( 'hidetoc', hidden ? '1' : null );
		} );

		// Initial state
		if ( mw.cookie.get( 'hidetoc' ) === '1' ) {
			toggleNode.checked = true;
			hidden = true;
		}
	}

	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var tocs = $content[ 0 ] ? $content[ 0 ].querySelectorAll( '.toc' ) : [],
			i = tocs.length;
		while ( i-- ) {
			initToc( tocs[ i ] );
		}
	} );
}() );
