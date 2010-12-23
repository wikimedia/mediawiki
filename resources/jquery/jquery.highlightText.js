/**
 * Plugin that highlights matched word partials in a given element
 * TODO: add a function for restoring the previous text
 * TODO: accept mappings for converting shortcuts like WP: to Wikipedia: 
 */
( function( $ ) {

$.highlightText = {
	
	// Split our pattern string at spaces and run our highlight function on the results
	splitAndHighlight: function( node, pat ) {
		var patArray = pat.split(" ");
		for ( var i = 0; i < patArray.length; i++ ) {
			if ( patArray[i].length == 0 ) continue;
			$.highlightText.innerHighlight( node, patArray[i] );
		}
		return node;
	},
	// scans a node looking for the pattern and wraps a span around each match 
	innerHighlight: function( node, pat ) {
		// if this is a text node
		if ( node.nodeType == 3 ) {
			// TODO - need to be smarter about the character matching here. 
			// non latin characters can make regex think a new word has begun. 
			// look for an occurence of our pattern and store the starting position 
			var pos = node.data.search( new RegExp( "\\b" + $.escapeRE( pat ), "i" ) );
			if ( pos >= 0 ) {
				// create the span wrapper for the matched text
				var spannode = document.createElement( 'span' );
				spannode.className = 'highlight';
				// shave off the characters preceding the matched text
				var middlebit = node.splitText( pos );
				// shave off any unmatched text off the end
				middlebit.splitText( pat.length );
				// clone for appending to our span
				var middleclone = middlebit.cloneNode( true );
				// append the matched text node to the span
				spannode.appendChild( middleclone );
				// replace the matched node, with our span-wrapped clone of the matched node
				middlebit.parentNode.replaceChild( spannode, middlebit );
			}
		// if this is an element with childnodes, and not a script, style or an element we created
		} else if ( node.nodeType == 1 && node.childNodes && !/(script|style)/i.test( node.tagName )
				&& !( node.tagName.toLowerCase() == 'span' && node.className.match( /\bhighlight/ ) ) ) {
			for ( var i = 0; i < node.childNodes.length; ++i ) {
				// call the highlight function for each child node
				$.highlightText.innerHighlight( node.childNodes[i], pat );
			}
		}
	}
};

$.fn.highlightText = function( matchString ) {
	return $( this ).each( function() {
		var $this = $( this );
		$this.data( 'highlightText', { originalText: $this.text() } );
		$.highlightText.splitAndHighlight( this, matchString );
	} );
};

} )( jQuery );

