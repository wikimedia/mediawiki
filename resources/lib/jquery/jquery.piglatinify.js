( function ( $ ) {

	$.fn.piglatinify = function() {

		convertWords = function( node ) {
			var textArray = node.split(' ');
			$( textArray ).each( function( index ) {
				// Make sure word is actually a word
				if ( textArray[index].length > 0 && textArray[index].match( /\w+/ ) ) {
					// Convert word to piglatin word
					textArray[index] = textArray[index].replace(/([^aeiou]*)([aeiou]\w*)/, '$2$1ay');
				}
			} );
			return textArray.join(' ');
		};

		return this.each( function( index, element ) {
			var node,
				tw = document.createTreeWalker( element, NodeFilter.SHOW_TEXT );
			while ( tw.nextNode() ) {
				node = tw.currentNode;
				node.nodeValue = convertWords( node.nodeValue );
			}
		} );

	};

}( jQuery ) );
