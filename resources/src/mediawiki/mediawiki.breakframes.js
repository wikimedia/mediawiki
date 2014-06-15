/*!
 * JavaScript to break out of framesets.
 */
( function () {
	var win = window;
	// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
	// it works only comparing to window.self or window.window (http://stackoverflow.com/q/4850978/319266)
	if ( win.top !== win.self ) {
		// Un-trap us from framesets
		win.top.location = win.location;
	}
}() );
