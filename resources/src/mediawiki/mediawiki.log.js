/*!
 * Logger for MediaWiki javascript.
 * Implements the stub left by the main 'mediawiki' module.
 *
 * @author Michael Dale <mdale@wikimedia.org>
 * @author Trevor Parscal <tparscal@wikimedia.org>
 */

( function ( mw, $ ) {

	// Keep reference to the dummy placeholder from mediawiki.js
	// The root is replaced below, but it has other methods that we need to restore.
	var original = mw.log,
		slice = Array.prototype.slice;

	mw.log = function () {
		// Turn arguments into an array
		var args = slice.call( arguments ),
			// Allow log messages to use a configured prefix to identify the source window (ie. frame)
			prefix = mw.config.exists( 'mw.log.prefix' ) ? mw.config.get( 'mw.log.prefix' ) + '> ' : '';

		// Try to use an existing console
		// Generally we can cache this, but in this case we want to re-evaluate this as a
		// global property live so that things like Firebug Lite can take precedence.
		if ( window.console && window.console.log && window.console.log.apply ) {
			args.unshift( prefix );
			window.console.log.apply( window.console, args );
			return;
		}

		// If there is no console, use our own log box
		mw.loader.using( 'jquery.footHovzer', function () {

			var	hovzer,
				d = new Date(),
				// Create HH:MM:SS.MIL timestamp
				time = ( d.getHours() < 10 ? '0' + d.getHours() : d.getHours() ) +
					':' + ( d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes() ) +
					':' + ( d.getSeconds() < 10 ? '0' + d.getSeconds() : d.getSeconds() ) +
					'.' + ( d.getMilliseconds() < 10 ? '00' + d.getMilliseconds() : ( d.getMilliseconds() < 100 ? '0' + d.getMilliseconds() : d.getMilliseconds() ) ),
				$log = $( '#mw-log-console' );

			if ( !$log.length ) {
				$log = $( '<div id="mw-log-console"></div>' ).css( {
					overflow: 'auto',
					height: '150px',
					backgroundColor: 'white',
					borderTop: 'solid 2px #ADADAD'
				} );
				hovzer = $.getFootHovzer();
				hovzer.$.append( $log );
				hovzer.update();
			}
			$log.append(
				$( '<div>' )
					.css( {
						borderBottom: 'solid 1px #DDDDDD',
						fontSize: 'small',
						fontFamily: 'monospace',
						whiteSpace: 'pre-wrap',
						padding: '0.125em 0.25em'
					} )
					.text( prefix + args.join( ', ' ) )
					.prepend( '<span style="float: right;">[' + time + ']</span>' )
			);
		} );
	};

	// Restore original methods
	mw.log.warn = original.warn;
	mw.log.error = original.error;
	mw.log.deprecate = original.deprecate;

}( mediaWiki, jQuery ) );
