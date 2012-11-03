( function( mw, $ ) {
	$.jqplot.config.enablePlugins = true;
	$( document ).ready( function() {
		$.jqplot( 'edithistory', editHistoryData, {
			title: mw.message( 'pageinfo-analytics-edithistory' ).escaped(),
			axes: {
				xaxis: {
					ticks: editHistoryLabels,
					tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					tickOptions: {
						angle: -40
					}
				}
			}
		} );
	} );
}( mediaWiki, jQuery ) );
