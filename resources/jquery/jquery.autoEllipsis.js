/**
 * Plugin that automatically truncates the plain text contents of an element and adds an ellipsis
 */
( function( $ ) {

// Cache ellipsed substrings for every string-width combination
var cache = { };
// Use a seperate cache when match highlighting is enabled
var matchTextCache = { };

$.fn.autoEllipsis = function( options ) {
	options = $.extend( {
		'position': 'center',
		'tooltip': false,
		'restoreText': false,
		'hasSpan': false,
		'matchText': null
	}, options );
	$(this).each( function() {
		var $this = $(this);
		if ( options.restoreText ) {
			if ( ! $this.data( 'autoEllipsis.originalText' ) ) {
				$this.data( 'autoEllipsis.originalText', $this.text() );
			} else {
				$this.text( $this.data( 'autoEllipsis.originalText' ) );
			}
		}
		
		// container element - used for measuring against
		var $container = $this;
		// trimmable text element - only the text within this element will be trimmed
		var $trimmableText = null;
		// protected text element - the width of this element is counted, but next is never trimmed from it
		var $protectedText = null;

		if ( options.hasSpan ) {
			$trimmableText = $this.children( options.selector );
		} else {
			$trimmableText = $( '<span />' )
				.css( 'whiteSpace', 'nowrap' )
				.text( $this.text() );
			$this
				.empty()
				.append( $trimmableText );
		}
		
		var text = $container.text();
		var trimmableText = $trimmableText.text();
		var w = $container.width();
		var pw = $protectedText ? $protectedText.width() : 0;
		// Try cache
		if ( !( text in cache ) ) {
			cache[text] = {};
		}
		if ( options.matchText && !( text in matchTextCache ) ) {
			matchTextCache[text] = {};
		}
		if ( options.matchText && !( options.matchText in matchTextCache[text] ) ) {
			matchTextCache[text][options.matchText] = {};
		}
		if ( !options.matchText && w in cache[text] ) {
			$container.html( cache[text][w] );
			if ( options.tooltip )
				$container.attr( 'title', text );
			return;
		}
		if( options.matchText && options.matchText in matchTextCache[text] && w in matchTextCache[text][options.matchText] ) {
			$container.html( matchTextCache[text][options.matchText][w] );
			if ( options.tooltip )
				$container.attr( 'title', text );
			return;
		}
		if ( $trimmableText.width() + pw > w ) {
			switch ( options.position ) {
				case 'right':
					// Use binary search-like technique for efficiency
					var l = 0, r = trimmableText.length;
					do {
						var m = Math.ceil( ( l + r ) / 2 );
						$trimmableText.text( trimmableText.substr( 0, m ) + '...' );
						if ( $trimmableText.width() + pw > w ) {
							// Text is too long
							r = m - 1;
						} else {
							l = m;
						}
					} while ( l < r );
					$trimmableText.text( trimmableText.substr( 0, l ) + '...' );
					break;
				case 'center':
					// TODO: Use binary search like for 'right'
					var i = [Math.round( trimmableText.length / 2 ), Math.round( trimmableText.length / 2 )];
					var side = 1; // Begin with making the end shorter
					while ( $trimmableText.outerWidth() + pw > w  && i[0] > 0 ) {
						$trimmableText.text( trimmableText.substr( 0, i[0] ) + '...' + trimmableText.substr( i[1] ) );
						// Alternate between trimming the end and begining
						if ( side == 0 ) {
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
					var r = 0;
					while ( $trimmableText.outerWidth() + pw > w && r < trimmableText.length ) {
						$trimmableText.text( '...' + trimmableText.substr( r ) );
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
			matchTextCache[text][options.matchText][w] = $container.html();
		} else {
			cache[text][w] = $container.html();
		}
		
	} );
};

} )( jQuery );