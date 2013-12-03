/**
 * jQuery fullscreen plugin v1.0.1
 * https://github.com/theopolisme/jquery-fullscreen/tree/v1.0.1
 *
 * Documentation at <https://github.com/theopolisme/jquery-fullscreen/blob/v1.0.1/README.md>
 *
 * Copyright 2013 Theopolisme <theopolismewiki@gmail.com>
 * Licensed under the MIT license <http://www.opensource.org/licenses/mit-license.php>
 */
( function ( $ ) {
	var setupFullscreen;

	/**
	 * On fullscreenchange, trigger appropriate event (either fullscreen or defullscreen)
	 * and also remove the 'fullscreened' class from elements that are no longer fullscreen
	 */
	function handleFullscreenChange () {
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
	}

	function enterFullscreen () {
		var element = this.get(0);
		if ( element ) {
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
			// Add the 'fullscreened' class to `element`
			this.first().addClass( 'fullscreened' );
		} else {
			return false;
		}
	}

	function exitFullscreen () {
		var fullscreenElement = ( document.fullscreenElement ||
				document.mozFullScreenElement ||
				document.webkitFullscreenElement ||
				document.msFullscreenElement );

		// Ensure that we only exit fullscreen if exitFullscreen() is being called on the same element that is currently fullscreen
		if ( fullscreenElement && this.get(0) === fullscreenElement ) {
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
			// We don't need need to remove the 'fullscreened' class here,
			// because it will be removed in handleFullscreenChange.
		} else {
			// This element is already out of fullscreen
			return true;
		}
	}

	/**
	 * Set up fullscreen handling and install necessary event handlers.
	 * Return false if fullscreen is not supported.
	 */
	setupFullscreen = function () {
		if ( document.fullscreenEnabled ||
				document.mozFullScreenEnabled ||
				document.webkitFullscreenEnabled ||
				document.msFullscreenEnabled
		) {
			// When the fullscreen mode is changed, trigger the
			// defullscreen or fullscreen events (and when exiting,
			// also remove the `fullscreened` class)
			$( document ).on( 'fullscreenchange webkitfullscreenchange mozfullscreenchange msfullscreenchange', handleFullscreenChange);
			// Convenience wrapper so that one only needs to listen for
			// 'fullscreenerror', not all of the prefixed versions
			$( document ).on( 'webkitfullscreenerror mozfullscreenerror msfullscreenerror', function () {
				$( document ).trigger( $.Event( 'fullscreenerror' ) );
			} );
			// Fullscreen has been set up, so always return true
			setupFullscreen = function () {
				return true;
			};
			return true;
		} else {
			// Always return false from now on, since fullscreen is not supported
			setupFullscreen = function () { return false; };
			return false;
		}
	};

	/**
	 * Set up fullscreen handling if necessary, then make the first element
	 * matching the given selector fullscreen
	 *
	 * @return {boolean} Fullscreen was enabled successfully
	 */
	$.fn.enterFullscreen = function () {
		if ( setupFullscreen() ) {
			$.fn.enterFullscreen = enterFullscreen;
			return this.enterFullscreen();
		} else {
			$.fn.enterFullscreen = function () { return false; };
			return false;
		}
	};

	/**
	 * Set up fullscreen handling if necessary, then cancel fullscreen mode
	 * for the first element matching the given selector.
	 *
	 * @return {boolean} The selected element is no longer in fullscreen mode
	 */
	$.fn.exitFullscreen = function () {
		if ( setupFullscreen() ) {
			$.fn.exitFullscreen = exitFullscreen;
			return this.exitFullscreen();
		} else {
			$.fn.exitFullscreen = function () { return false; };
			return false;
		}
	};
}( jQuery ) );
