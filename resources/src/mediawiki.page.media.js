/*!
 * Add magnify links to thumbs and resize broken media spans, where needed
 */
( function () {
	mw.hook( 'wikipage.content' ).add( ( $content ) => {
		$content.find(
			'figure[typeof~="mw:File/Thumb"] > :not(figcaption) .mw-file-element'
		).each( function () {
			var inner = this.parentNode;
			var wrapper = inner.parentNode;

			if ( this.classList.contains( 'mw-broken-media' ) ) {
				// Resize broken media spans, where needed
				var isDefault = wrapper.classList.contains( 'mw-default-size' );
				if ( isDefault ) {
					return;
				}
				if ( this.hasAttribute( 'data-width' ) ) {
					this.style.width = this.getAttribute( 'data-width' ) + 'px';
				}
				if ( this.hasAttribute( 'data-height' ) ) {
					this.style.height = this.getAttribute( 'data-height' ) + 'px';
				}
			} else {
				// Add magnify links to thumbs, where needed
				var resource = this.getAttribute( 'resource' );
				if ( !resource ) {
					return;
				}
				if ( inner.classList.contains( 'mw-file-description' ) ) {
					return;
				}
				var desc = this.ownerDocument.createElement( 'a' );
				desc.setAttribute( 'href', resource );
				// Using a different class than mw-file-description to avoid the
				// expectation that the media will be found inside it
				desc.classList.add( 'mw-file-magnify' );
				wrapper.insertBefore( desc, inner.nextSibling );
			}
		} );
	} );
}() );
