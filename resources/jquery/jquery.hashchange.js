/**
 * jQuery plugin to fix cross-browser bugs with onhashchange.
 * This does not implement a fallback for browsers that don't support it.
 * It merely fixes bugs with those browsers that do.
 *
 * Upstream request: http://bugs.jquery.com/ticket/11924
 *
 * @author Krinkle, 2012
 */
( function ( $ ) {
	// Check browser support.
	// IE8 running in IE7 compatibility mode returns true for the 'in' check
	// (while the event, just like in real IE7, is not supported).
	// So also test document.documentMode.
	var docMode = document.documentMode;

	$.support.hashchange = 'onhashchange' in window && ( !docMode || docMode > 7 );

	// In Opera 11.1 and 11.5 `'onhashchange' in window` returns true,
	// but it does not support window.addEventListener( 'hashchange', .. );
	// This makes it hard to do graceful degregation, because the fallback
	// is likely in the else case, but doesn't get executed since the support
	// check returned true.
	// Therefore we tell jQuery to use the window.onhashchange property (instead
	// of addEventListener). This also takes care of multiple binded handlers
	// (internally it keeps a callback list etc.).

	// Based on core $.event.special.beforeunload:
	$.event.special.hashchange = {
		setup: function ( data, ns, handle ) {
			// We only want to do this special case on windows
			if ( $.isWindow( this ) ) {
				this.onhashchange = handle;
			}
		}
	};

}( jQuery ) );
