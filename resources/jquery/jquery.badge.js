/**
 * jQuery Badge plugin
 *
 * Based on Badger plugin by Daniel Raftery (http://thrivingkings.com/badger).
 *
 * @license MIT
 */

/**
 * @author Ryan Kaldari <rkaldari@wikimedia.org>, 2012
 * @author Andrew Garrett <agarrett@wikimedia.org>, 2012
 * @author Marius Hoch <hoo@online.de>, 2012
 *
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
( function ( $ ) {

	/**
	 * Allows you to put a numeric "badge" on an item on the page.
	 * See mediawiki.org/wiki/ResourceLoader/Default_modules#jQuery.badge
	 *
	 * @param {string|number} badgeCount An explicit number, or "+n"/ "-n"
	 *  to modify the existing value. If the new value is equal or lower than 0,
	 *  any existing badge will be removed. The badge container will be appended
	 *  to the selected element(s).
	 * @param {Object} options Optional parameters specified below
	 *   type: 'inline' or 'overlay' (default)
	 *   callback: will be called with the number now shown on the badge as a parameter
	 */
	$.fn.badge = function ( badgeCount, options ) {
		var $badge,
			oldBadgeCount,
			newBadgeCount,
			$existingBadge = this.find( '.mw-badge' );

		options = $.extend( { type : 'overlay' }, options );

		// If there is no existing badge, this will give an empty string
		oldBadgeCount = Number( $existingBadge.text() );
		if ( isNaN( oldBadgeCount ) ) {
			oldBadgeCount = 0;
		}

		// If badgeCount is a number, use that as the new badge
		if ( typeof badgeCount === 'number' ) {
			newBadgeCount = badgeCount;
		} else if ( typeof badgeCount === 'string' ) {
			// If badgeCount is "+x", add x to the old badge
			if ( badgeCount.charAt(0) === '+' ) {
				newBadgeCount = oldBadgeCount + Number( badgeCount.substr(1) );
			// If badgeCount is "-x", subtract x from the old badge
			} else if ( badgeCount.charAt(0) === '-' ) {
				newBadgeCount = oldBadgeCount - Number( badgeCount.substr(1) );
			// If badgeCount can be converted into a number, convert it
			} else if ( !isNaN( Number( badgeCount ) ) ) {
				newBadgeCount = Number( badgeCount );
			} else {
				newBadgeCount = 0;
			}
		// Other types are not supported, fall back to 0.
		} else {
			newBadgeCount = 0;
		}

		// Badge count must be a whole number
		newBadgeCount = Math.round( newBadgeCount );

		if ( newBadgeCount <= 0 ) {
			// Badges should only exist for values > 0.
			$existingBadge.remove();
		} else {
			// Don't add duplicates
			if ( $existingBadge.length ) {
				$badge = $existingBadge;
				// Insert the new count into the badge
				this.find( '.mw-badge-content' ).text( newBadgeCount );
			} else {
				// Contruct a new badge with the count
				$badge = $( '<div>' )
					.addClass( 'mw-badge' )
					.append(
						$( '<span>' )
							.addClass( 'mw-badge-content' )
							.text( newBadgeCount )
					);
				this.append( $badge );
			}

			if ( options.type === 'inline' ) {
				$badge
					.removeClass( 'mw-badge-overlay' )
					.addClass( 'mw-badge-inline' );
			// Default: overlay
			} else {
				$badge
					.removeClass( 'mw-badge-inline' )
					.addClass( 'mw-badge-overlay' );

			}

			// If a callback was specified, call it with the badge count
			if ( $.isFunction( options.callback ) ) {
				options.callback( newBadgeCount );
			}
		}
	};
}( jQuery ) );
