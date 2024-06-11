( function () {
	'use strict';

	function initToc( tocNode ) {
		const toggleNode = tocNode.querySelector( '.toctogglecheckbox' );

		if ( !toggleNode ) {
			return;
		}

		let hidden = false;

		toggleNode.addEventListener( 'change', () => {
			hidden = !hidden;
			mw.cookie.set( 'hidetoc', hidden ? '1' : null );
		} );

		// Initial state
		if ( mw.cookie.get( 'hidetoc' ) === '1' ) {
			toggleNode.checked = true;
			hidden = true;
		}
	}

	mw.hook( 'wikipage.content' ).add( ( $content ) => {
		const tocs = $content[ 0 ] ? $content[ 0 ].querySelectorAll( '.toc' ) : [];
		let i = tocs.length;
		while ( i-- ) {
			initToc( tocs[ i ] );
		}
	} );
}() );
