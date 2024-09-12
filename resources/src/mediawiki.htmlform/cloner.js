/*
 * HTMLForm enhancements:
 * Add/remove cloner clones without having to resubmit the form.
 */

let cloneCounter = 0;

/**
 * Appends a new row with fields to the cloner.
 *
 * @ignore
 * @param {jQuery} $createButton
 */
function appendToCloner( $createButton ) {
	const $ul = $createButton.prev( 'ul.mw-htmlform-cloner-ul' ),

		cloneRegex = new RegExp( mw.util.escapeRegExp( $ul.data( 'uniqueId' ) ), 'g' ),
		// Assume the ids that need to be made unique will start with 'ooui-php-'. See T274533
		inputIdRegex = new RegExp( /(ooui-php-[0-9]*)/, 'gm' );

	++cloneCounter;
	const html = $ul.data( 'template' )
		.replace( cloneRegex, 'clone' + cloneCounter )
		.replace( inputIdRegex, '$1-clone' + cloneCounter );

	const $li = $( '<li>' )
		.addClass( 'mw-htmlform-cloner-li' )
		.html( html )
		.appendTo( $ul );

	mw.hook( 'htmlform.enhance' ).fire( $li );
}

mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
	const $deleteElement = $root.find( '.mw-htmlform-cloner-delete-button' ),
		$createElement = $root.find( '.mw-htmlform-cloner-create-button' );

	$deleteElement.each( function () {
		const $element = $( this );

		// eslint-disable-next-line no-jquery/no-class-state
		if ( $element.hasClass( 'oo-ui-widget' ) ) {
			const deleteButton = OO.ui.infuse( $element );
			deleteButton.on( 'click', () => {
				deleteButton.$element.closest( 'li.mw-htmlform-cloner-li' ).remove();
			} );
		} else {
			// eslint-disable-next-line no-jquery/no-sizzle
			$element.filter( ':input' ).on( 'click', function ( e ) {
				e.preventDefault();
				$( this ).closest( 'li.mw-htmlform-cloner-li' ).remove();
			} );
		}
	} );

	$createElement.each( function () {
		const $element = $( this );

		// eslint-disable-next-line no-jquery/no-class-state
		if ( $element.hasClass( 'oo-ui-widget' ) ) {
			const createButton = OO.ui.infuse( $element );
			createButton.on( 'click', () => {
				appendToCloner( createButton.$element );
			} );
		} else {
			// eslint-disable-next-line no-jquery/no-sizzle
			$element.filter( ':input' ).on( 'click', function ( e ) {
				e.preventDefault();
				appendToCloner( $( this ) );
			} );
		}
	} );
} );
