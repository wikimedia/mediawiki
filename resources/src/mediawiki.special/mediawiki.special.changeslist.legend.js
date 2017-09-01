/*!
 * Script for changes list legend
 */

/* Remember the collapse state of the legend on recent changes and watchlist pages. */
( function ( mw ) {
	var
		cookieName = 'changeslist-state',
		// Expanded by default
		doCollapsibleLegend = function ( $container ) {
			$container.find( '.mw-changeslist-legend' )
				.makeCollapsible( {
					collapsed: mw.cookie.get( cookieName ) === 'collapsed'
				} )
				.on( 'beforeExpand.mw-collapsible', function () {
					mw.cookie.set( cookieName, 'expanded' );
				} )
				.on( 'beforeCollapse.mw-collapsible', function () {
					mw.cookie.set( cookieName, 'collapsed' );
				} );
		};

	mw.hook( 'wikipage.content' ).add( doCollapsibleLegend );
}( mediaWiki ) );
