/*
 * Implementation for mediaWiki.log stub
 */

(function( $ ) {

	/**
	 * Log output to the console.
	 *
	 * In the case that the browser does not have a console available, one is created by appending a
	 * <div> element to the bottom of the body and then appending a <div> element to that for each
	 * message.
	 *
	 * @author Michael Dale <mdale@wikimedia.org>
	 * @author Trevor Parscal <tparscal@wikimedia.org>
	 * @param logmsg string Message to output to console.
	 */
	mw.log = function( logmsg ) {
		// Allow log messages to use a configured prefix to identify the source window (ie. frame)
		if ( mw.config.exists( 'mw.log.prefix' ) ) {
			logmsg = mw.config.get( 'mw.log.prefix' ) + '> ' + logmsg;
		}
		// Try to use an existing console
		if ( window.console !== undefined && $.isFunction( window.console.log ) ) {
			window.console.log( logmsg );
		} else {
			// Set timestamp
			var d = new Date();
			var time = ( d.getHours() < 10 ? '0' + d.getHours() : d.getHours() ) +
				 ':' + ( d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes() ) +
				 ':' + ( d.getSeconds() < 10 ? '0' + d.getSeconds() : d.getSeconds() ) +
				 '.' + ( d.getMilliseconds() < 10 ? '00' + d.getMilliseconds() : ( d.getMilliseconds() < 100 ? '0' + d.getMilliseconds() : d.getMilliseconds() ) );
			// Show a log box for console-less browsers
			var $log = $( '#mw-log-console' );
			if ( !$log.length ) {
				$log = $( '<div id="mw-log-console"></div>' )
					.css( {
						'position': 'fixed',
						'overflow': 'auto',
						'z-index': 500,
						'bottom': '0px',
						'left': '0px',
						'right': '0px',
						'height': '150px',
						'background-color': 'white',
						'border-top': 'solid 2px #ADADAD'
					} );
				$( 'body' )
					.css( 'padding-bottom', '150px' ) // don't hide anything
					.append( $log );
			}
			$log.append(
				$( '<div></div>' )
					.css( {
						'border-bottom': 'solid 1px #DDDDDD',
						'font-size': 'small',
						'font-family': 'monospace',
						'white-space': 'pre-wrap',
						'padding': '0.125em 0.25em'
					} )
					.text( logmsg )
					.prepend( '<span style="float:right">[' + time + ']</span>' )
			);
		}
	};

})(jQuery);
