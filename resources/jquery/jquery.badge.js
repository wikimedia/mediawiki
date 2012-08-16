// Badger v1.0 by Daniel Raftery
// http://thrivingkings.com/badger
// http://twitter.com/ThrivingKings
// Modified by Ryan Kaldari <rkaldari@wikimedia.org>

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

(function( $ ) {
	$.fn.badge = function( badge, options ) {
		var oldBadge;
		var existingBadge = this.find( '.mw-badge' );
		options = $.extend( {}, options );

		if ( badge.charAt(0) === '+' ) {
			if ( existingBadge.length > 0 ) {
				oldBadge = existingBadge.text();
				badge = Math.round( Number( oldBadge ) + Number( badge.substr(1) ) );
			} else {
				badge = badge.substr(1);
			}
		} else if ( badge.charAt(0) === '-' ) {
			if ( existingBadge.length > 0 ) {
				oldBadge = existingBadge.text();
				badge = Math.round( Number( oldBadge ) - Number( badge.substr(1) ) );
			} else {
				badge = 0;
			}
		}

		if ( Number(badge) <= 0 ) {
			// Clear any existing badge
			existingBadge.remove();
		} else {
			// Don't add duplicates
			var $badge = existingBadge;
			if ( existingBadge.length > 0 ) {
				this.find( '.mw-badge-content' ).text( badge );
			} else {
				$badge = $('<div class="mw-badge mw-badge-overlay"></div>')
					.append(
						$('<span class="mw-badge-content"></span>')
							.text(badge)
					);
				this.append($badge);
			}

			//change $badge classes if inline, use defaults (overlay) otherwise
			if ( options.type && options.type === 'inline' ) {
				$badge.removeClass('mw-badge-overlay')
					.addClass('mw-badge-inline');
			}

			// If a callback was specified and is a function, call it with the badge number
			if ( typeof( options.callback ) === 'function' ) {
				options.callback( badge );
			}
		}
	};
} ) ( jQuery );
