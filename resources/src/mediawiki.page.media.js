/*!
 * Add magnify links to thumbs, where needed
 */
( function () {
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		$content.find(
			'figure[typeof~="mw:File/Thumb"] > :not(figcaption) .mw-file-element'
		).each( function () {
			var resource = this.getAttribute( 'resource' );
			if ( !resource ) {
				return;
			}
			var inner = this.parentNode;
			if ( inner.classList.contains( 'mw-file-description' ) ) {
				return;
			}
			var desc = this.ownerDocument.createElement( 'a' );
			desc.setAttribute( 'href', resource );
			// Using a different class than mw-file-description to avoid the
			// expectation that the media will be found inside it
			desc.classList.add( 'mw-file-magnify' );
			inner.parentNode.insertBefore( desc, inner.nextSibling );
		} );
	} );
}() );
