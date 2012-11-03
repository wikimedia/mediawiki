( function( mw, $ ) {
	$( document ).ready( function() {
		$.jqplot( 'edithistory', editHistoryData, {
			title: mw.message( 'pageinfo-analytics-edithistory' ).escaped(),
			axes: {
				xaxis: {
					ticks: editHistoryLabels
				}
			}
		} );
	} );
}( mediaWiki, jQuery ) );
