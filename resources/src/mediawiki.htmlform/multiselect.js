/*
 * HTMLForm enhancements:
 * Convert multiselect fields from checkboxes to Chosen selector when requested.
 */

function convertCheckboxesWidgetToTags( fieldLayout ) {
	const checkboxesWidget = fieldLayout.fieldWidget;
	const checkboxesOptions = checkboxesWidget.checkboxMultiselectWidget.getItems();
	const menuTagOptions = checkboxesOptions.map( ( option ) => new OO.ui.MenuOptionWidget( {
		data: option.getData(),
		label: option.getLabel(),
		disabled: option.disabled // Don't take the state of parent elements into account.
	} ) );
	const fieldData = checkboxesWidget.data || {};
	const menuTagWidget = new OO.ui.MenuTagMultiselectWidget( {
		$overlay: true,
		menu: {
			items: menuTagOptions
		},
		disabled: checkboxesWidget.isDisabled(),
		placeholder: fieldData.placeholder || ''
	} );
	menuTagWidget.setValue( checkboxesWidget.getValue() );

	menuTagOptions.forEach( ( option ) => {
		if ( option.disabled ) {
			const tagItem = menuTagWidget.findItemFromData( option.getData() );
			// When this disabled option is selected by default.
			if ( tagItem ) {
				tagItem.setFixed( true );
			}
		}
	} );

	// Data from TagMultiselectWidget will not be submitted with the form, so keep the original
	// CheckboxMultiselectInputWidget up-to-date.
	menuTagWidget.on( 'change', () => {
		checkboxesWidget.setValue( menuTagWidget.getValue() );
	} );
	// Synchronize the disable state for submission, and set the proper state of the label.
	menuTagWidget.on( 'disable', ( isDisabled ) => {
		checkboxesWidget.setDisabled( isDisabled );
	} );
	// Change the connected fieldWidget to the new one, so other scripts can infuse the layout
	// and make changes to this widget.
	fieldLayout.fieldWidget = menuTagWidget;

	// Hide original widget and add new one in its place.
	checkboxesWidget.toggle( false );
	checkboxesWidget.$element.after( menuTagWidget.$element );
}

mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
	const $dropdowns = $root.find( '.mw-htmlform-dropdown:not(.oo-ui-widget)' );
	if ( $dropdowns.length ) {
		$dropdowns.each( function () {
			const $el = $( this );
			if ( $el.is( '[data-ooui]' ) ) {
				// Avoid kicks in multiple times and causing a mess
				if ( $el.find( '.oo-ui-menuTagMultiselectWidget' ).length ) {
					return;
				}
				// Load 'oojs-ui-widgets' for TagMultiselectWidget
				const modules = [ 'mediawiki.htmlform.ooui', 'oojs-ui-widgets' ];
				const data = $el.data( 'mw-modules' );
				if ( data ) {
					// We can trust this value, 'data-mw-*' attributes are banned from user content in Sanitizer
					const extraModules = data.split( ',' );
					modules.push( ...extraModules );
				}
				mw.loader.using( modules, () => {
					convertCheckboxesWidgetToTags( OO.ui.FieldLayout.static.infuse( $el ) );
				} );
			}
		} );
	}
} );
