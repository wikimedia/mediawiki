/*!
 * JavaScript for Special:Import
 */
$( () => {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Import' ) {
		return;
	}

	let projectDropdownInput, subprojectDropdownInput;

	function updateImportSubprojectList() {
		const project = projectDropdownInput.getValue();
		subprojectDropdownInput.dropdownWidget.getMenu().getItems().forEach( ( item ) => {
			item.toggle( item.getData().startsWith( project + '::' ) );
		} );

		const firstItem = subprojectDropdownInput.dropdownWidget.menu.findFirstSelectableItem();
		subprojectDropdownInput.toggle( !!firstItem );
		subprojectDropdownInput.dropdownWidget.menu.selectItem( firstItem );
	}

	const $subprojectInput = $( '#mw-input-subproject' );
	if ( $subprojectInput.length ) {
		projectDropdownInput = OO.ui.infuse( $( '#mw-input-interwiki' ) );
		subprojectDropdownInput = OO.ui.infuse( $subprojectInput );

		projectDropdownInput.on( 'change', updateImportSubprojectList );
		updateImportSubprojectList();
	}

	// HACK: Move namespace selector next to the corresponding input, between other radio inputs.
	const sources = [ 'upload', 'interwiki' ];
	sources.forEach( ( name ) => {
		// IDs: mw-import-mapping-upload, mw-import-mapping-interwiki
		const $mappingInput = $( '#mw-import-mapping-' + name );
		// IDs: mw-import-namespace-upload, mw-import-namespace-interwiki
		const $namespaceInput = $( '#mw-import-namespace-' + name );

		if ( $mappingInput.length && $namespaceInput.length ) {
			const mappingWidget = OO.ui.infuse( $mappingInput );
			const namespaceWidget = OO.ui.infuse( $namespaceInput );

			const $namespaceRadio = mappingWidget.radioSelectWidget.findItemFromData( 'namespace' ).$element;
			const $namespaceField = namespaceWidget.$element.closest( '.oo-ui-fieldLayout' );
			$namespaceRadio.after( $namespaceField );
		}
	} );
} );
