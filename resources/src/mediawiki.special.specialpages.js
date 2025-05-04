let index, texts;
function buildIndex() {
	index = {};
	const $rows = $( '.mw-special-pages-row' );
	$rows.each( function () {
		const $row = $( this );
		const $labels = $row.find(
			'td'
		);

		function addToIndex( $label ) {
			const text = $label.val() || $label[ 0 ].textContent.toLowerCase().trim().replace( /\s+/, ' ' );
			if ( text ) {
				index[ text ] = index[ text ] || [];
				index[ text ].push( {
					$row: $row
				} );
			}
		}

		$labels.each( function () {
			addToIndex( $( this ) );
		} );

		// Add aliases to index
		for ( let i = 0; i < $row[ 0 ].attributes.length; i++ ) {
			if ( $row[ 0 ].attributes[ i ].name.startsWith( 'data-search-index-' ) ) {
				const text = $row[ 0 ].attributes[ i ].value;
				if ( text ) {
					index[ text ] = index[ text ] || [];
					index[ text ].push( {
						$row: $row
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

	$( '.mw-special-pages-search-highlight' ).removeClass( 'mw-special-pages-search-highlight' );
	if ( isSearching ) {
		$( '.cdx-table__table tbody' ).addClass( 'mw-special-pages-is-searching' );
		val = val.toLowerCase();
		texts.forEach( ( text ) => {
			if ( text.includes( val ) ) {
				index[ text ].forEach( ( item ) => {
					item.$row.addClass( 'mw-special-pages-search-highlight' );
				} );
			}
		} );
	} else {
		$( '.cdx-table__table tbody' ).removeClass( 'mw-special-pages-is-searching' );
	}
} );

// Handle the initial value in case the user started typing before this JS code loaded,
// or the browser restored the value for a closed tab
if ( search.getValue() ) {
	search.emit( 'change', search.getValue() );
}
