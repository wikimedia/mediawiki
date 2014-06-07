( function ( mw, $ ) {
/**
 * Function that escapes spaces in event names. This is needed because
 * "_delayedBind-foo bar-1000" refers to two events
 */
function encodeEvent( event ) {
	return event.replace( /-/g, '--' ).replace( / /g, '-' );
}

$.fn.extend( {
	/**
	 * Bind a callback to an event in a delayed fashion.
	 * In detail, this means that the callback will be called a certain
	 * time after the event fires, but the timer is reset every time
	 * the event fires.
	 * @param timeout Number of milliseconds to wait
	 * @param event Name of the event (string)
	 * @param data Data to pass to the event handler (optional)
	 * @param callback Function to call
	 */
	delayedBind: function ( timeout, event, data, callback ) {
		if ( arguments.length === 3 ) {
			// Shift optional parameter down
			callback = data;
			data = undefined;
		}
		var encEvent = encodeEvent( event );
		return this.each( function () {
			var that = this;
			// Bind the top half
			// Do this only once for every (event, timeout) pair
			if (  !( $(this).data( '_delayedBindBound-' + encEvent + '-' + timeout ) ) ) {
				$(this).data( '_delayedBindBound-' + encEvent + '-' + timeout, true );
				$(this).bind( event, function () {
					var timerID = $(this).data( '_delayedBindTimerID-' + encEvent + '-' + timeout );
					// Cancel the running timer
					if ( timerID !== null ) {
						clearTimeout( timerID );
					}
					timerID = setTimeout( function () {
						$(that).trigger( '_delayedBind-' + encEvent + '-' + timeout );
					}, timeout );
					$(this).data( '_delayedBindTimerID-' + encEvent + '-' + timeout, timerID );
				} );
			}

			// Bottom half
			$(this).bind( '_delayedBind-' + encEvent + '-' + timeout, data, callback );
		} );
	},

	/**
	 * Cancel the timers for delayed events on the selected elements.
	 */
	delayedBindCancel: function ( timeout, event ) {
		var encEvent = encodeEvent( event );
		return this.each( function () {
			var timerID = $(this).data( '_delayedBindTimerID-' + encEvent + '-' + timeout );
			if ( timerID !== null ) {
				clearTimeout( timerID );
			}
		} );
	},

	/**
	 * Unbind an event bound with delayedBind()
	 */
	delayedBindUnbind: function ( timeout, event, callback ) {
		var encEvent = encodeEvent( event );
		return this.each( function () {
			$(this).unbind( '_delayedBind-' + encEvent + '-' + timeout, callback );
		} );
	}
} );

mw.log.deprecate( $.fn, 'delayedBind', $.fn.delayedBind,
	'Use the jquery.throttle-debounce module instead' );
mw.log.deprecate( $.fn, 'delayedBindCancel', $.fn.delayedBindCancel,
	'Use the jquery.throttle-debounce module instead' );
mw.log.deprecate( $.fn, 'delayedBindUnbind', $.fn.delayedBindUnbind,
	'Use the jquery.throttle-debounce module instead' );

}( mediaWiki, jQuery ) );
