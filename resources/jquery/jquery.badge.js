// jQuery Badge plugin
// Based on Badger plugin by Daniel Raftery (http://thrivingkings.com/badger)
// Modified by:
//     Ryan Kaldari <rkaldari@wikimedia.org>
//     Andrew Garrett <agarrett@wikimedia.org>

/**
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * This program is distributed WITHOUT ANY WARRANTY.
 */

(function ( $ ) {

	/**
	 * Allows you to put a numeric "badge" on an item on the page
	 * See https://www.mediawiki.org/wiki/ResourceLoader/Default_modules#jQuery.badge
	 * @param  string|int  badgeCount  An explicit number, or "+n"/ "-n" to modify the existing value
	 * @param  object      options     Optional parameters specified below
	 *   type: 'inline' or 'overlay'
	 *   callback: will be called with the number now shown on the badge as a parameter
	 */
	$.fn.badge = function( badgeCount, options ) {
		var existingBadge = this.find( '.mw-badge' );
		var newBadgeCount = 0;
		options = $.extend( {}, options );

		// if badgeCount is a number, use that as the new badge
		if ( typeof badgeCount === 'number' ) {
			newBadgeCount = badgeCount;
		} else if ( typeof badgeCount === 'string' ) {
			// if badgeCount is "+x", add x to the old badge
			if ( badgeCount.charAt(0) === '+' ) {
				if ( existingBadge.length > 0 ) {
					var oldBadgeCount = existingBadge.text();
					newBadgeCount = Number( oldBadgeCount ) + Number( badgeCount.substr(1) );
				} else {
					newBadgeCount = Number( badge.substr(1) );
				}
			// if badgeCount is "-x", subtract x from the old badge
			} else if ( badgeCount.charAt(0) === '-' ) {
				if ( existingBadge.length > 0 ) {
					var oldBadgeCount = existingBadge.text();
					newBadgeCount = Number( oldBadgeCount ) - Number( badgeCount.substr(1) );
				}
			// if badgeCount can be converted into a number, convert it
			} else if ( !isNaN( badgeCount ) ) {
				newBadgeCount = Number( badgeCount );
			}
		}

		if ( isNaN( newBadgeCount ) ) {
			newBadgeCount = 0;
		} else {
			// Badge count must be a whole number
			newBadgeCount = Math.round( newBadgeCount );
		}

		if ( newBadgeCount <= 0 ) {
			// Clear any existing badge
			existingBadge.remove();
		} else {
			// Don't add duplicates
			if ( existingBadge.length > 0 ) {
				var $badge = existingBadge;
				// Insert the new count into the badge
				this.find( '.mw-badge-content' ).text( newBadgeCount );
			} else {
				// Contruct a new badge with the count
				var $badge = $( '<div></div>' )
					.addClass( 'mw-badge' )
					.addClass( 'mw-badge-overlay' )
					.append(
						$( '<span></span>' )
							.addClass( 'mw-badge-content' )
							.text( newBadgeCount )
					);
				this.append( $badge );
			}

			if ( options.type ) {
				if ( options.type === 'inline' ) {
					$badge.removeClass( 'mw-badge-overlay' )
						.addClass( 'mw-badge-inline' );
				} else if ( options.type === 'overlay' ) {
					$badge.removeClass( 'mw-badge-inline' )
						.addClass( 'mw-badge-overlay' );
				}
			}

			// If a callback was specified, call it with the badge count
			if ( options.callback ) {
				options.callback( newBadgeCount );
			}
		}
	};
} ) ( jQuery );
