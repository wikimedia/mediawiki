/*!
 * JavaScript for Special:Import
 */
( function ( $ ) {
	function updateImportSubprojectList( firstTime ) {
		var $projectField = $( '#mw-import-table-interwiki #interwiki' ),
			$subprojectField = $projectField.parent().find( '#subproject' ),
			$selected = $projectField.find( ':selected' ),
			oldValue = $subprojectField.val(),
			option, options;

		if ( $selected.attr( 'data-subprojects' ) ) {
			options = $.map( $selected.attr( 'data-subprojects' ).split( ' ' ), function ( el ) {
				option = document.createElement( 'option' );
				option.appendChild( document.createTextNode( el ) );
				option.setAttribute( 'value', el );
				if ( oldValue === el && firstTime !== true ) {
					option.setAttribute( 'selected', 'selected' );
				}
				return option;
			} );
			$subprojectField.show().empty().append( options );
		} else {
			$subprojectField.hide();
		}
	}

	$( function () {
		var $projectField = $( '#mw-import-table-interwiki #interwiki' );
		if ( $projectField.length ) {
			$projectField.change( updateImportSubprojectList );
			updateImportSubprojectList( true );
		}
	} );
}( jQuery ) );
