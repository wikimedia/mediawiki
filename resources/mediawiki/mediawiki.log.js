/**
 * Logger for MediaWiki javascript.
 * Implements the stub left by the main 'mediawiki' module.
 *
 * @author Michael Dale <mdale@wikimedia.org>
 * @author Trevor Parscal <tparscal@wikimedia.org>
 */

(function( $ ) {

	/**
	 * Logs a message to the console.
	 *
	 * In the case the browser does not have a console API, a console is created on-the-fly by appending
	 * a <div id="mw-log-console"> element to the bottom of the body and then appending this and future
	 * messages to that, instead of the console.
	 *
	 * @param logmsg {String} Messages to output to the console.
	 */
	mw.log = function( logmsg ) {
		// Allow log messages to use a configured prefix to identify the source window (ie. frame)
		if ( mw.config.exists( 'mw.log.prefix' ) ) {
			logmsg = mw.config.get( 'mw.log.prefix' ) + '> ' + logmsg;
		}

		// Try to use an existing console
		if ( window.console !== undefined && $.isFunction( window.console.log ) ) {
			window.console.log( logmsg );
			return;
		}

		// If there is no console, use our own log box

			var	d = new Date(),
				// Create HH:MM:SS.MIL timestamp
				time = ( d.getHours() < 10 ? '0' + d.getHours() : d.getHours() ) +
				 ':' + ( d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes() ) +
				 ':' + ( d.getSeconds() < 10 ? '0' + d.getSeconds() : d.getSeconds() ) +
				 '.' + ( d.getMilliseconds() < 10 ? '00' + d.getMilliseconds() : ( d.getMilliseconds() < 100 ? '0' + d.getMilliseconds() : d.getMilliseconds() ) ),
				 $log = $( '#mw-log-console' );
	
			if ( !$log.length ) {
				$log = $( '<div id="mw-log-console"></div>' ).css( {
						position: 'fixed',
						overflow: 'auto',
						zIndex: 500,
						bottom: '0px',
						left: '0px',
						right: '0px',
						height: '150px',
						backgroundColor: 'white',
						borderTop: 'solid 2px #ADADAD'
					} );
				$( 'body' )
					// Since the position is fixed, make sure we don't hide any actual content.
					// Increase padding to account for #mw-log-console.
					.css( 'paddingBottom', '+=150px' )
					.append( $log );
			}
			$log.append(
				$( '<div></div>' )
					.css( {
						borderBottom: 'solid 1px #DDDDDD',
						fontSize: 'small',
						fontFamily: 'monospace',
						whiteSpace: 'pre-wrap',
						padding: '0.125em 0.25em'
					} )
					.text( logmsg )
					.prepend( '<span style="float: right;">[' + time + ']</span>' )
		);
	};

})( jQuery );
