/*!
 * JavaScript for Special:Import
 */
$( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Import' ) {
		return;
	}

	var projectDropdownInput, subprojectDropdownInput;

	function updateImportSubprojectList() {
		var project = projectDropdownInput.getValue();
		subprojectDropdownInput.dropdownWidget.getMenu().getItems().forEach( function ( item ) {
			item.toggle( item.getData().indexOf( project + '::' ) === 0 );
		} );

		var firstItem = subprojectDropdownInput.dropdownWidget.menu.findFirstSelectableItem();
		subprojectDropdownInput.toggle( !!firstItem );
		subprojectDropdownInput.dropdownWidget.menu.selectItem( firstItem );
	}

	var $subprojectInput = $( '#mw-input-subproject' );
	if ( $subprojectInput.length ) {
		projectDropdownInput = OO.ui.infuse( $( '#mw-input-interwiki' ) );
		subprojectDropdownInput = OO.ui.infuse( $subprojectInput );

		projectDropdownInput.on( 'change', updateImportSubprojectList );
		updateImportSubprojectList();
	}

	// HACK: Move namespace selector next to the corresponding input, between other radio inputs.
	var sources = [ 'upload', 'interwiki' ];
	sources.forEach( function ( name ) {
		// IDs: mw-import-mapping-upload, mw-import-mapping-interwiki
		var $mappingInput = $( '#mw-import-mapping-' + name );
		// IDs: mw-import-namespace-upload, mw-import-namespace-interwiki
		var $namespaceInput = $( '#mw-import-namespace-' + name );

		if ( $mappingInput.length && $namespaceInput.length ) {
			var mappingWidget = OO.ui.infuse( $mappingInput );
			var namespaceWidget = OO.ui.infuse( $namespaceInput );

			var $namespaceRadio = mappingWidget.radioSelectWidget.findItemFromData( 'namespace' ).$element;
			var $namespaceField = namespaceWidget.$element.closest( '.oo-ui-fieldLayout' );
			$namespaceRadio.after( $namespaceField );
		}
	} );
} );
