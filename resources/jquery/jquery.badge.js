/**
 * jQuery Badge plugin
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
	 * @param {string|number} badgeValue The value to display in the badge. If
	 *  the new value is numeric and equal to or lower than 0, any existing
	 *  badge will be removed. The badge container will be appended to the
	 *  selected element(s).
	 * @param {Object} options Optional parameters specified below:
	 *   type: 'inline' or 'overlay' (default)
	 *   callback: will be called with the value now shown on the badge as a parameter
	 */
	$.fn.badge = function ( badgeValue, options ) {
		var $badge,
			newBadgeValue,
			newBadgeValueIsNumeric = true,
			$existingBadge = this.find( '.mw-badge' );

		// Set overlay as default badge type.
		options = $.extend( { type : 'overlay' }, options );

		// If badgeValue is a number, use that as the new badge value.
		if ( typeof badgeValue === 'number' && badgeValue !== NaN ) {
			newBadgeValue = badgeValue;
		} else if ( typeof badgeValue === 'string' ) {
			// If badgeValue can be converted into a number, convert it.
			// Empty string will be converted to 0.
			} else if ( !isNaN( badgeValue ) ) {
				newBadgeValue = Number( badgeValue );
			// Otherwise, just use the string as the new badge value.
			} else {
				newBadgeValue = badgeValue;
				newBadgeValueIsNumeric = false;
			}
		} else {
			// badgeValue is neither a number nor a string
			return this;
		}

		if ( newBadgeValueIsNumeric ) {
			// Numeric badge values must be whole numbers.
			newBadgeValue = Math.round( newBadgeValue );
			if ( newBadgeValue <= 0 ) {
				// Badges should only exist for values > 0.
				$existingBadge.remove();
				return this;
			}
		}

		// Don't add duplicate badges.
		if ( $existingBadge.length ) {
			$badge = $existingBadge;
			// Insert the new value into the badge.
			this.find( '.mw-badge-content' ).text( newBadgeValue );
		} else {
			// Construct a new badge with the value.
			$badge = $( '<div>' )
				.addClass( 'mw-badge' )
				.append(
					$( '<span>' )
						.addClass( 'mw-badge-content' )
						.text( newBadgeValue )
				);
			this.append( $badge );
		}

		// Style the badge according to the type
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

		// If a callback was specified, call it with the badge value.
		if ( $.isFunction( options.callback ) ) {
			options.callback( newBadgeValue );
		}
		return this;
	};
}( jQuery ) );
