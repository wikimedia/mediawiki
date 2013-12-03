/**
 * jQuery fullscreen plugin
 *
 * Events you can listen for:
 *  - fullscreen, triggered when the page goes fullscreen
 *  - defullscreen, triggered when the page goes back to normal
 *
 * The element that is made fullscreen will have the 'fullscreened'
 * class while it is fullscreen.
 *
 * == Usage ==
 *
 * First, initialize the fullscreen plugin (required for doing anything else):
 * 	$.fullscreenSetup();
 *
 * Make an element go fullscreen:
 * 	$element.fullscreen();
 *
 * Then take it out of fullscreen:
 * 	$element.fullscreen();
 *
 * == Important note ==
 *
 * Elements can only be made fullscreen based on user interaction
 * (for example, clicking a button), to protect against malicious uses
 * of the fullscreen functionality.
 *
 * @author Theopolisme 2013
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
