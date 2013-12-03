/**
 * jQuery fullscreen plugin
 * https://github.com/theopolisme/jquery-fullscreen
 *
 * Documentation at <https://github.com/theopolisme/jquery-fullscreen/blob/master/README.md>
 *
 * Copyright 2013 Theopolisme <theopolismewiki@gmail.com>
 * Licensed under the MIT license <http://www.opensource.org/licenses/mit-license.php>
 */
( function ( $ ) {
	// Set up fullscreen handling and install necessary event handlers.
	// Return false if fullscreen is not supported.
	$.fullscreenSetup = function () {
		// Only set up once
		if ( $.fn.fullscreen ) {
			return true;
		}

		if ( document.fullscreenEnabled ||
				document.mozFullScreenEnabled ||
				document.webkitFullscreenEnabled ||
				document.msFullscreenEnabled ) {
			// When the fullscreen mode is changed, trigger the
			// defullscreen or fullscreen events (and when exiting,
			// also remove the `fullscreened` class)
			$( document ).on( 'webkitfullscreenchange mozfullscreenchange fullscreenchange msfullscreenchange', function () {
				if ( !document.fullscreenElement &&
						!document.mozFullScreenElement &&
						!document.webkitFullscreenElement &&
						!document.msFullscreenElement ) {
					$( '.fullscreened' ).removeClass( 'fullscreened' );
					$( document ).trigger( $.Event( 'defullscreen' ) );
				} else {
					// We don't mess with styling here because we don't know if we
					// induced the fullscreening or if it was something else
					$( document ).trigger( $.Event( 'fullscreen' ) );
				}
			} );

			// Change an element's fullscreen state. Turn non-fullscreen elements fullscreen and
			// fullscreen elements non-fullscreen.
			$.fn.fullscreen = function () {
					var element;

					// Cancel fullscreen mode if it is enabled
					if ( document.fullscreenElement ||
							document.mozFullScreenElement ||
							document.webkitFullscreenElement ||
							document.msFullscreenElement ) {
						if ( document.exitFullscreen ) {
							document.exitFullscreen();
						} else if ( document.mozCancelFullScreen ) {
							document.mozCancelFullScreen();
						} else if ( document.webkitCancelFullScreen ) {
							document.webkitCancelFullScreen();
						} else if ( document.msCancelFullScreen ) {
							document.msCancelFullScreen();
						} else {
							// Unable to cancel fullscreen mode
							return false;
						}

						this.removeClass( 'fullscreened' );
					// Otherwise, enable it
					} else {
						element = this.get(0);
						if ( element.requestFullscreen ) {
							element.requestFullscreen();
						} else if ( element.mozRequestFullScreen ) {
							element.mozRequestFullScreen();
						} else if ( element.webkitRequestFullscreen ) {
							element.webkitRequestFullscreen();
						} else if ( element.msRequestFullscreen ) {
							element.msRequestFullscreen();
						} else {
							// Unable to make fullscreen
							return false;
						}

						this.addClass( 'fullscreened' );
					}
			};
		} else {
			return false;
		}
	};
}( jQuery ) );
