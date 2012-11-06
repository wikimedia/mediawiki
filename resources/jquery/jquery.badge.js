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
	 * Allows you to put a "badge" on an item on the page. The badge container
	 * will be appended to the selected element(s).
	 * See mediawiki.org/wiki/ResourceLoader/Default_modules#jQuery.badge
	 *
	 * @param text The value to display in the badge. If the value is 0, null,
	 *   false, or '' (empty string), any existing badge will be removed.
	 * @param boolean inline True if the badge should be displayed inline, false
	 *   if the badge should overlay the parent element (default is inline)
	 */
	$.fn.badge = function ( text, inline ) {
		var div, $badge = this.find( '.mw-badge' );

		if ( text ) {
			// If a badge already exists, reuse it
			if ( $badge.length ) {
				$badge.find( '.mw-badge-content' ).text( text );
				return $badge;
			}
			// Otherwise, create a new badge with the specified text and style
			div = document.createElement( 'div' );
			div.className = 'mw-badge mw-badge-' + ( inline ? 'inline' : 'overlay' );
			div.innerHTML = '<span class="mw-badge-content">' + text + '</span>';
			return $( div ).appendTo( this );
		} else {
			$badge.remove();
			return this;
		}
	};
}( jQuery ) );
