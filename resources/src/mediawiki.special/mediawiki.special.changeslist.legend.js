/*!
 * Script for changes list legend
 */

/* Remember the collapse state of the legend on recent changes and watchlist pages. */
jQuery( document ).ready( function ( $ ) {
	var
		cookieName = 'changeslist-state',
		cookieOptions = {
			expires: 30,
			path: '/'
		},
		isCollapsed = mw.cookie( cookieName ) === 'collapsed';

	$( '.mw-changeslist-legend' )
		.makeCollapsible( {
			collapsed: isCollapsed
		} )
		.on( 'beforeExpand.mw-collapsible', function () {
			mw.cookie( cookieName, 'expanded', cookieOptions );
		} )
		.on( 'beforeCollapse.mw-collapsible', function () {
			mw.cookie( cookieName, 'collapsed', cookieOptions );
		} );
} );
