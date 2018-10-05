/**
 * Plugin that highlights matched word partials in a given element.
 * TODO: Add a function for restoring the previous text.
 * TODO: Accept mappings for converting shortcuts like WP: to Wikipedia:.
 */
( function () {

	$.highlightText = {

		// Split our pattern string at spaces and run our highlight function on the results
		splitAndHighlight: function ( node, text ) {
			var i,
				words = text.split( ' ' );
			for ( i = 0; i < words.length; i++ ) {
				if ( words[ i ].length === 0 ) {
					continue;
				}
				$.highlightText.innerHighlight(
					node,
					new RegExp( '(^|\\s)' + mw.RegExp.escape( words[ i ] ), 'i' )
				);
			}
			return node;
		},

		prefixHighlight: function ( node, prefix ) {
			$.highlightText.innerHighlight(
				node,
				new RegExp( '(^)' + mw.RegExp.escape( prefix ), 'i' )
			);
		},

		// scans a node looking for the pattern and wraps a span around each match
		innerHighlight: function ( node, pat ) {
			var i, match, pos, spannode, middlebit, middleclone;
			if ( node.nodeType === Node.TEXT_NODE ) {
				// TODO - need to be smarter about the character matching here.
				// non Latin characters can make regex think a new word has begun: do not use \b
				// http://stackoverflow.com/questions/3787072/regex-wordwrap-with-utf8-characters-in-js
				// look for an occurrence of our pattern and store the starting position
				match = node.data.match( pat );
				if ( match ) {
					pos = match.index + match[ 1 ].length; // include length of any matched spaces
					// create the span wrapper for the matched text
					spannode = document.createElement( 'span' );
					spannode.className = 'highlight';
					// shave off the characters preceding the matched text
					middlebit = node.splitText( pos );
					// shave off any unmatched text off the end
					middlebit.splitText( match[ 0 ].length - match[ 1 ].length );
					// clone for appending to our span
					middleclone = middlebit.cloneNode( true );
					// append the matched text node to the span
					spannode.appendChild( middleclone );
					// replace the matched node, with our span-wrapped clone of the matched node
					middlebit.parentNode.replaceChild( spannode, middlebit );
				}
			} else if (
				node.nodeType === Node.ELEMENT_NODE &&
				// element with childnodes, and not a script, style or an element we created
				node.childNodes &&
				!/(script|style)/i.test( node.tagName ) &&
				!(
					node.tagName.toLowerCase() === 'span' &&
					node.className.match( /\bhighlight/ )
				)
			) {
				for ( i = 0; i < node.childNodes.length; ++i ) {
					// call the highlight function for each child node
					$.highlightText.innerHighlight( node.childNodes[ i ], pat );
				}
			}
		}
	};

	/**
	 * Highlight certain text in current nodes (by wrapping it in `<span class="highlight">...</span>`).
	 *
	 * @param {string} matchString String to match
	 * @param {Object} [options]
	 * @param {string} [options.method='splitAndHighlight'] Method of matching to use, one of:
	 *   - 'splitAndHighlight': Split `matchString` on spaces, then match each word separately.
	 *   - 'prefixHighlight': Match `matchString` at the beginning of text only.
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.highlightText = function ( matchString, options ) {
		options = options || {};
		options.method = options.method || 'splitAndHighlight';
		return this.each( function () {
			var $el = $( this );
			$el.data( 'highlightText', { originalText: $el.text() } );
			$.highlightText[ options.method ]( this, matchString );
		} );
	};

}() );
