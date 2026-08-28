/*!
 * JavaScript for Special:EditTags
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'EditTags' ) {
		return;
	}

	$( () => {
		mw.widgets.visibleCodePointLimit(
			OO.ui.infuse( $( '#wpReason' ) ),
			mw.config.get( 'wgCommentCodePointLimit' )
		);

		const $wpRemoveAll = $( '#mw-input-wpRemoveAllTags' );
		if ( !$wpRemoveAll.length ) {
			return;
		}

		const removeAllWidget = OO.ui.infuse( $wpRemoveAll );
		const removeField = OO.ui.infuse( $( '.mw-edittags-remove-tags:not(.oo-ui-widget)' ) );
		const selectWidget = removeField.fieldWidget.checkboxMultiselectWidget;
		const allItems = selectWidget.getItems();

		removeAllWidget.on( 'change', ( state ) => {
			selectWidget.selectItems( state ? allItems : [] );
		} );

		removeField.fieldWidget.on( 'change', ( data ) => {
			removeAllWidget.setSelected( data.length === allItems.length );
		} );
	} );
}() );
