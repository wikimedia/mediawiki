/* jshint -W024*/
( function ( mw, $ ) {
	$( function() {
		mw.widgets.DateInputWidget.static.infuse( 'mw-date-start' );
		mw.widgets.DateInputWidget.static.infuse( 'mw-date-end' );
	} );
}( mediaWiki, jQuery ) );
