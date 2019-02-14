/*!
 * Script for changes list legend
 */

/* Remember the collapse state of the legend on recent changes and watchlist pages. */
mw.hook( 'wikipage.content' ).add( function ( $container ) {
	$container.find( '.mw-changeslist-legend' )
		.on( 'toggle', function () {
			mw.cookie.set( 'changeslist-state', this.open ? 'expanded' : 'collapsed' );
		} );
} );
