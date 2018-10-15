/*
 * HTMLForm enhancements:
 * Add/remove cloner clones without having to resubmit the form.
 */
( function () {

	var cloneCounter = 0;

	/**
	 * Appends a new row with fields to the cloner.
	 *
	 * @ignore
	 * @param {jQuery} $createButton
	 */
	function appendToCloner( $createButton ) {
		var $li,
			$ul = $createButton.prev( 'ul.mw-htmlform-cloner-ul' ),
			html = $ul.data( 'template' ).replace(
				new RegExp( mw.RegExp.escape( $ul.data( 'uniqueId' ) ), 'g' ),
				'clone' + ( ++cloneCounter )
			);

		$li = $( '<li>' )
			.addClass( 'mw-htmlform-cloner-li' )
			.html( html )
			.appendTo( $ul );

		mw.hook( 'htmlform.enhance' ).fire( $li );
	}

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $deleteElement = $root.find( '.mw-htmlform-cloner-delete-button' ),
			$createElement = $root.find( '.mw-htmlform-cloner-create-button' ),
			createButton;

		$deleteElement.each( function () {
			var $element = $( this ),
				deleteButton;

			if ( $element.hasClass( 'oo-ui-widget' ) ) {
				deleteButton = OO.ui.infuse( $element );
				deleteButton.on( 'click', function () {
					deleteButton.$element.closest( 'li.mw-htmlform-cloner-li' ).remove();
				} );
			} else {
				$element.filter( ':input' ).click( function ( ev ) {
					ev.preventDefault();
					$( this ).closest( 'li.mw-htmlform-cloner-li' ).remove();
				} );
			}
		} );

		if ( $createElement.hasClass( 'oo-ui-widget' ) ) {
			createButton = OO.ui.infuse( $createElement );
			createButton.on( 'click', function () {
				appendToCloner( createButton.$element );
			} );
		} else {
			$createElement.filter( ':input' ).click( function ( ev ) {
				ev.preventDefault();

				appendToCloner( $( this ) );
			} );
		}
	} );

}() );
