let index, texts;
function buildIndex() {
	index = {};
	const $items = $( '.mw-specialpages-list li' );
	$items.each( function () {
		const $item = $( this );
		const $group = $item.closest( '.mw-specialpages-list' ).prev( '.mw-specialpagesgroup' );

		function addToIndex( $label ) {
			const text = $label.val() || $label[ 0 ].textContent.toLowerCase().trim().replace( /\s+/, ' ' );
			if ( text ) {
				index[ text ] = index[ text ] || [];
				index[ text ].push( {
					$item: $item,
					$group: $group
				} );
			}
		}

		addToIndex( $item );
		addToIndex( $group );
		// Future: Add other text associated with the item to the index, e.g. a description label

		// Add aliases to index
		for ( let i = 0; i < $item[ 0 ].attributes.length; i++ ) {
			if ( $item[ 0 ].attributes[ i ].name.startsWith( 'data-search-index-' ) ) {
				const text = $item[ 0 ].attributes[ i ].value;
				if ( text ) {
					index[ text ] = index[ text ] || [];
					index[ text ].push( {
						$item: $item,
						$group: $group
					} );
				}
			}
		}
	} );
	texts = Object.keys( index );
}

const searchWrapper = OO.ui.infuse( $( '.mw-special-pages-search' ) );
const search = searchWrapper.fieldWidget;
search.$input.on( 'focus', () => {
	if ( !index ) {
		// Lazy-build index on first focus
		buildIndex();
	}
} );
search.on( 'change', ( val ) => {
	if ( !index ) {
		buildIndex();
	}
	const isSearching = !!val;

	const $content = $( '#mw-content-text' );
	$( '.mw-special-pages-search-highlight' ).removeClass( 'mw-special-pages-search-highlight' );
	if ( isSearching ) {
		$content.addClass( 'mw-special-pages-is-searching' );
		val = val.toLowerCase();
		texts.forEach( ( text ) => {
			if ( text.includes( val ) ) {
				index[ text ].forEach( ( item ) => {
					item.$item.addClass( 'mw-special-pages-search-highlight' );
					item.$group.addClass( 'mw-special-pages-search-highlight' );
				} );
			}
		} );
	} else {
		$content.removeClass( 'mw-special-pages-is-searching' );
	}
} );

// Handle the initial value in case the user started typing before this JS code loaded,
// or the browser restored the value for a closed tab
if ( search.getValue() ) {
	search.emit( 'change', search.getValue() );
}
