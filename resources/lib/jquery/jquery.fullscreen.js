/**
 * jQuery fullscreen plugin v2.0.0-git (9f8f97d127)
 * https://github.com/theopolisme/jquery-fullscreen
 *
 * Copyright (c) 2013 Theopolisme <theopolismewiki@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */
( function ( $ ) {
	var setupFullscreen,
		fsClass = 'jq-fullscreened';

	/**
	 * On fullscreenchange, trigger a jq-fullscreen-change event
	 * The event is given an object, which contains the fullscreened DOM element (element), if any
	 * and a boolean value (fullscreen) indicating if we've entered or exited fullscreen mode
	 * Also remove the 'fullscreened' class from elements that are no longer fullscreen
	 */
	function handleFullscreenChange () {
		var fullscreenElement = document.fullscreenElement ||
			document.mozFullScreenElement ||
			document.webkitFullscreenElement ||
			document.msFullscreenElement;

		if ( !fullscreenElement ) {
			$( '.' + fsClass ).data( 'isFullscreened', false ).removeClass( fsClass );
		}

		$( document ).trigger( $.Event( 'jq-fullscreen-change', { element: fullscreenElement, fullscreen: !!fullscreenElement } ) );
	}

	/**
	 * Enters full screen with the "this" element in focus.
	 * Check the .data( 'isFullscreened' ) of the return value to check
	 * success or failure, if you're into that sort of thing.
	 * @chainable
	 * @return {jQuery}
	 */
	function enterFullscreen () {
		var element = this.get(0),
			$element = this.first();
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
				$element.data( 'isFullscreened', false );
				return this;
			}
			// Add the fullscreen class and data attribute to `element`
			$element.addClass( fsClass ).data( 'isFullscreened', true );
			return this;
		} else {
			$element.data( 'isFullscreened', false );
			return this;
		}
	}

	/**
	 * Brings the "this" element out of fullscreen.
	 * Check the .data( 'isFullscreened' ) of the return value to check
	 * success or failure, if you're into that sort of thing.
	 * @chainable
	 * @return {jQuery}
	 */
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
			} else if ( document.msExitFullscreen ) {
				document.msExitFullscreen();
			} else {
				// Unable to cancel fullscreen mode
				return this;
			}
			// We don't need to remove the fullscreen class here,
			// because it will be removed in handleFullscreenChange.
			// But we should change the data on the element so the
			// caller can check for success.
			this.first().data( 'isFullscreened', false );
		}

		return this;
	}

	/**
	 * Set up fullscreen handling and install necessary event handlers.
	 * Return false if fullscreen is not supported.
	 */
	setupFullscreen = function () {
		if ( $.support.fullscreen ) {
			// When the fullscreen mode is changed, trigger the
			// fullscreen events (and when exiting,
			// also remove the fullscreen class)
			$( document ).on( 'fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', handleFullscreenChange);
			// Convenience wrapper so that one only needs to listen for
			// 'fullscreenerror', not all of the prefixed versions
			$( document ).on( 'webkitfullscreenerror mozfullscreenerror MSFullscreenError', function () {
				$( document ).trigger( $.Event( 'fullscreenerror' ) );
			} );
			// Fullscreen has been set up, so always return true
			setupFullscreen = function () { return true; };
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
	 * @chainable
	 * @return {jQuery}
	 */
	$.fn.enterFullscreen = function () {
		if ( setupFullscreen() ) {
			$.fn.enterFullscreen = enterFullscreen;
			return this.enterFullscreen();
		} else {
			$.fn.enterFullscreen = function () { return this; };
			return this;
		}
	};

	/**
	 * Set up fullscreen handling if necessary, then cancel fullscreen mode
	 * for the first element matching the given selector.
	 * @chainable
	 * @return {jQuery}
	 */
	$.fn.exitFullscreen = function () {
		if ( setupFullscreen() ) {
			$.fn.exitFullscreen = exitFullscreen;
			return this.exitFullscreen();
		} else {
			$.fn.exitFullscreen = function () { return this; };
			return this;
		}
	};

	$.support.fullscreen = document.fullscreenEnabled ||
		document.webkitFullscreenEnabled ||
		document.mozFullScreenEnabled ||
		document.msFullscreenEnabled;
}( jQuery ) );
