/*!
 * jQuery Badge plugin
 *
 * @license MIT
 *
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

/**
 * @class jQuery.plugin.badge
 */
( function ( $, mw ) {
	/**
	 * Put a badge on an item on the page. The badge container will be appended to the selected element(s).
	 *
	 *     $element.badge( text );
	 *     $element.badge( 5 );
	 *     $element.badge( '100+' );
	 *     $element.badge( text, inline );
	 *     $element.badge( 'New', true );
	 *
	 * @param {number|string} text The value to display in the badge. If the value is falsey (0,
	 *  null, false, '', etc.), any existing badge will be removed.
	 * @param {boolean} [inline=true] True if the badge should be displayed inline, false
	 *  if the badge should overlay the parent element.
	 * @param {boolean} [displayZero=false] True if the number zero should be displayed,
	 *  false if the number zero should result in the badge being hidden
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.badge = function ( text, inline, displayZero ) {
		var $badge = this.find( '.mw-badge' ),
			badgeStyleClass = 'mw-badge-' + ( inline ? 'inline' : 'overlay' ),
			isImportant = true,
			displayBadge = true;

		// If we're displaying zero, ensure style to be non-important
		if ( mw.language.convertNumber( text, true ) === 0 ) {
			isImportant = false;
			if ( !displayZero ) {
				displayBadge = false;
			}
		// If text is falsey (besides 0), hide the badge
		} else if ( !text ) {
			displayBadge = false;
		}

		if ( displayBadge ) {
			// If a badge already exists, reuse it
			if ( $badge.length ) {
				$badge
					.toggleClass( 'mw-badge-important', isImportant )
					.find( '.mw-badge-content' )
						.text( text );
			} else {
				// Otherwise, create a new badge with the specified text and style
				$badge = $( '<div class="mw-badge"></div>' )
					.addClass( badgeStyleClass )
					.toggleClass( 'mw-badge-important', isImportant )
					.append(
						$( '<span class="mw-badge-content"></span>' ).text( text )
					)
					.appendTo( this );
			}
		} else {
			$badge.remove();
		}
		return this;
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.badge
	 */
}( jQuery, mediaWiki ) );
