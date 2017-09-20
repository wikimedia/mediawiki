/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	var $link = $( '<a>' )
		.text( mw.msg( 'rcfilters-open-interface' ) )
		.on( 'click', function () {

			mw.loader.using( [ 'mediawiki.rcfilters.filters.ui', 'mediawiki.rcfilters.filters.base.styles' ] )
				.then( function () {
					$( 'body' ).removeClass( 'rcfilters-lazyload' );

					// Update user option
					new mw.Api().saveOption( 'rcfilters-expand-ui', 1 );
					// Update the preference for this session
					mw.user.options.set( 'rcfilters-expand-ui', 1 );

					$link.detach();
				} );

			return false;
		} );

	$( '.rcfilters-head' ).append( $link );
}( mediaWiki, jQuery ) );
