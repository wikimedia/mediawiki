/**
 * @class jQuery.plugin.autoEllipsis
 */
( function ( $ ) {

var
	// Cache ellipsed substrings for every string-width-position combination
	cache = {},

	// Use a separate cache when match highlighting is enabled
	matchTextCache = {};

/**
 * Automatically truncate the plain text contents of an element and add an ellipsis
 *
 * @param {Object} options
 * @param {'center'|'left'|'right'} [options.position='center'] Where to remove text.
 * @param {boolean} [options.tooltip=false] Whether to show a tooltip with the remainder
 * of the text.
 * @param {boolean} [options.restoreText=false] Whether to save the text for restoring
 * later.
 * @param {boolean} [options.hasSpan=false] Whether the element is already a container,
 * or if the library should create a new container for it.
 * @param {string|null} [options.matchText=null] Text to highlight, e.g. search terms.
 * @return {jQuery}
 * @chainable
 */
$.fn.autoEllipsis = function ( options ) {
	options = $.extend( {
		position: 'center',
		tooltip: false,
		restoreText: false,
		hasSpan: false,
		matchText: null
	}, options );

	return this.each( function () {
		var $trimmableText,
			text, trimmableText, w, pw,
			l, r, i, side, m,
			// container element - used for measuring against
			$container = $( this );

		if ( options.restoreText ) {
			if ( !$container.data( 'autoEllipsis.originalText' ) ) {
				$container.data( 'autoEllipsis.originalText', $container.text() );
			} else {
				$container.text( $container.data( 'autoEllipsis.originalText' ) );
			}
		}

		// trimmable text element - only the text within this element will be trimmed
		if ( options.hasSpan ) {
			$trimmableText = $container.children( options.selector );
		} else {
			$trimmableText = $( '<span>' )
				.css( 'whiteSpace', 'nowrap' )
				.text( $container.text() );
			$container
				.empty()
				.append( $trimmableText );
		}

		text = $container.text();
		trimmableText = $trimmableText.text();
		w = $container.width();
		pw = 0;

		// Try cache
		if ( options.matchText ) {
			if ( !( text in matchTextCache ) ) {
				matchTextCache[text] = {};
			}
			if ( !( options.matchText in matchTextCache[text] ) ) {
				matchTextCache[text][options.matchText] = {};
			}
			if ( !( w in matchTextCache[text][options.matchText] ) ) {
				matchTextCache[text][options.matchText][w] = {};
			}
			if ( options.position in matchTextCache[text][options.matchText][w] ) {
				$container.html( matchTextCache[text][options.matchText][w][options.position] );
				if ( options.tooltip ) {
					$container.attr( 'title', text );
				}
				return;
			}
		} else {
			if ( !( text in cache ) ) {
				cache[text] = {};
			}
			if ( !( w in cache[text] ) ) {
				cache[text][w] = {};
			}
			if ( options.position in cache[text][w] ) {
				$container.html( cache[text][w][options.position] );
				if ( options.tooltip ) {
					$container.attr( 'title', text );
				}
				return;
			}
		}

		if ( $trimmableText.width() + pw > w ) {
			switch ( options.position ) {
				case 'right':
					// Use binary search-like technique for efficiency
					l = 0;
					r = trimmableText.length;
					do {
						m = Math.ceil( ( l + r ) / 2 );
						$trimmableText.text( trimmableText.slice( 0, m ) + '...' );
						if ( $trimmableText.width() + pw > w ) {
							// Text is too long
							r = m - 1;
						} else {
							l = m;
						}
					} while ( l < r );
					$trimmableText.text( trimmableText.slice( 0, l ) + '...' );
					break;
				case 'center':
					// TODO: Use binary search like for 'right'
					i = [Math.round( trimmableText.length / 2 ), Math.round( trimmableText.length / 2 )];
					// Begin with making the end shorter
					side = 1;
					while ( $trimmableText.outerWidth() + pw > w && i[0] > 0 ) {
						$trimmableText.text( trimmableText.slice( 0, i[0] ) + '...' + trimmableText.slice( i[1] ) );
						// Alternate between trimming the end and begining
						if ( side === 0 ) {
							// Make the begining shorter
							i[0]--;
							side = 1;
						} else {
							// Make the end shorter
							i[1]++;
							side = 0;
						}
					}
					break;
				case 'left':
					// TODO: Use binary search like for 'right'
					r = 0;
					while ( $trimmableText.outerWidth() + pw > w && r < trimmableText.length ) {
						$trimmableText.text( '...' + trimmableText.slice( r ) );
						r++;
					}
					break;
			}
		}
		if ( options.tooltip ) {
			$container.attr( 'title', text );
		}
		if ( options.matchText ) {
			$container.highlightText( options.matchText );
			matchTextCache[text][options.matchText][w][options.position] = $container.html();
		} else {
			cache[text][w][options.position] = $container.html();
		}

	} );
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.autoEllipsis
 */

}( jQuery ) );
