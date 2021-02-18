/*!
 * JavaScript for Special:Import
 */
$( function () {
	var projectDropdownInput, subprojectDropdownInput,
		sources = [];

	function updateImportSubprojectList() {
		var firstItem,
			project = projectDropdownInput.getValue();

		subprojectDropdownInput.dropdownWidget.getMenu().getItems().forEach( function ( item ) {
			item.toggle( item.getData().indexOf( project + '::' ) === 0 );
		} );

		firstItem = subprojectDropdownInput.dropdownWidget.menu.findFirstSelectableItem();
		subprojectDropdownInput.toggle( !!firstItem );
		subprojectDropdownInput.dropdownWidget.menu.selectItem( firstItem );
	}

	if ( $( '#mw-input-subproject' ).length ) {
		projectDropdownInput = OO.ui.infuse( $( '#mw-input-interwiki' ) );
		subprojectDropdownInput = OO.ui.infuse( $( '#mw-input-subproject' ) );

		projectDropdownInput.on( 'change', updateImportSubprojectList );
		updateImportSubprojectList();
	}

	if ( $( '#mw-input-xmlimport' ).length ) {
		sources.push( 'upload' );
	}
	if ( $( '#mw-input-interwiki' ).length ) {
		sources.push( 'interwiki' );
	}

	sources.forEach( function ( name ) {
		// Don't infuse the individual RadioSelectWidgets, broken up as a hack.
		var $radios = $( '#mw-import-' + name + '-form input[name=mapping]' ),
			namespace = OO.ui.infuse( $( '#mw-import-namespace-' + name ) ),
			rootpage = OO.ui.infuse( $( '#mw-interwiki-rootpage-' + name ) );

		// HACK: Move namespace selector next to the corresponding radio input.
		$radios.filter( '[value=namespace]' ).closest( '.oo-ui-fieldLayout' ).after(
			namespace.$element.closest( '.oo-ui-fieldLayout' )
		);

		function onRadioChange() {
			var value = $radios.filter( ':checked' ).val();
			namespace.setDisabled( value !== 'namespace' );
			rootpage.setDisabled( value !== 'subpage' );
		}

		$radios.on( 'change', onRadioChange );
		onRadioChange();
	} );
} );
