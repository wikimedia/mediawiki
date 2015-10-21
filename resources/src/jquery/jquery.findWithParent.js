( function ( $ ) {
	/** @class jQuery */

	/**
	 * Gives support to find parent elements using .closest with less-than selector syntax.
	 *
	 *     $.findWithParent( $div, "< html div < body" ); // find closest parent of $div "html", find child "div" of it, find closest parent "body" of that, return "body"
	 *     $( '#foo' ).findWithParent( '.bar < .baz' ); // find child ".bar" of "#foo", return closest parent ".baz" from there
	 *
	 * @method findWithParent
	 * @param {jQuery|HTMLElement|string} $context
	 * @param {string} selector
	 * @return {jQuery}
	 */
	function jQueryFindWithParent( $context, selector ) {
		var matches;

		$context = $( $context );
		selector = $.trim( selector );

		while ( selector && ( matches = selector.match( /(.*?(?:^|[>\s+~]))(<\s*[^>\s+~]+)(.*?)$/ ) ) ) {
			if ( $.trim( matches[ 1 ] ) ) {
				$context = $context.find( matches[ 1 ] );
			}
			if ( $.trim( matches[ 2 ] ) ) {
				$context = $context.closest( matches[ 2 ].substr( 1 ) );
			}
			selector = $.trim( matches[ 3 ] );
		}

		if ( selector ) {
			$context = $context.find( selector );
		}

		return $context;
	}

	$.findWithParent = jQueryFindWithParent;

	/** @class jQuery.fn */
	/**
	 * @param {string} selector
	 * @return {jQuery}
	 * @see jQuery#findWithParent
	 */
	$.fn.findWithParent = function ( selector ) {
		var selectors = selector.split( ',' ),
			$elements = $(),
			self = this;

		$.each( selectors, function ( i, selector ) {
			$elements = $elements.add( jQueryFindWithParent( self, selector ) );
		} );

		return $elements;
	};
}( jQuery ) );
