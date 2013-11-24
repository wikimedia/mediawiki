/*!
 * Logger for MediaWiki javascript.
 * Implements the stub left by the main 'mediawiki' module.
 *
 * @author Michael Dale <mdale@wikimedia.org>
 * @author Trevor Parscal <tparscal@wikimedia.org>
 */

( function ( mw, $ ) {

	/**
	 * @class mw.log
	 * @singleton
	 */

	/**
	 * Logs a message to the console.
	 *
	 * In the case the browser does not have a console API, a console is created on-the-fly by appending
	 * a `<div id="mw-log-console">` element to the bottom of the body and then appending this and future
	 * messages to that, instead of the console.
	 *
	 * @param {string...} msg Messages to output to console.
	 */
	mw.log = function () {
		// Turn arguments into an array
		var	args = Array.prototype.slice.call( arguments ),
			// Allow log messages to use a configured prefix to identify the source window (ie. frame)
			prefix = mw.config.exists( 'mw.log.prefix' ) ? mw.config.get( 'mw.log.prefix' ) + '> ' : '';

		// Try to use an existing console
		if ( window.console !== undefined && $.isFunction( window.console.log ) ) {
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

	/**
	 * Write a message the console's warning channel.
	 * Also logs a stacktrace for easier debugging.
	 * Each action is silently ignored if the browser doesn't support it.
	 *
	 * @param {string...} msg Messages to output to console
	 */
	mw.log.warn = function () {
		var console = window.console;
		if ( console && console.warn ) {
			console.warn.apply( console, arguments );
			if ( console.trace ) {
				console.trace();
			}
		}
	};

	/**
	 * Create a property in a host object that, when accessed, will produce
	 * a deprecation warning in the console with backtrace.
	 *
	 * @param {Object} obj Host object of deprecated property
	 * @param {string} key Name of property to create in `obj`
	 * @param {Mixed} val The value this property should return when accessed
	 * @param {string} [msg] Optional text to include in the deprecation message.
	 */
	mw.log.deprecate = !Object.defineProperty ? function ( obj, key, val ) {
		obj[key] = val;
	} : function ( obj, key, val, msg ) {
		msg = 'MWDeprecationWarning: Use of "' + key + '" property is deprecated.' +
			( msg ? ( ' ' + msg ) : '' );
		try {
			Object.defineProperty( obj, key, {
				configurable: true,
				enumerable: true,
				get: function () {
					mw.log.warn( msg );
					return val;
				},
				set: function ( newVal ) {
					mw.log.warn( msg );
					val = newVal;
				}
			} );
		} catch ( err ) {
			// IE8 can throw on Object.defineProperty
			obj[key] = val;
		}
	};

}( mediaWiki, jQuery ) );
