/**
 * JavaScript for Special:Import
 */
( function ( $ ) {
	function updateImportSubprojectList( ) {
		var $projectField = $( '#mw-import-table-interwiki #interwiki' ),
			$subprojectField = $projectField.parent( ).find( '#subproject' );

		var $selected = $projectField.find( ':selected' );
		if ( $selected.attr( 'data-subprojects' ) ) {
			/*$subprojectField.show( ).children( ).each( function( key, value ) {
				var $value = $( value );
				if ( subprojects.indexOf( $value.val( ) ) === -1 ) {
					$value.hide( );
					if ( $value.val( ) === $subprojectField.val( ) ) {
						$subprojectField.val( 0 ); // reset to first value
					}
				} else {
					$value.show( );
				}
			} );*/
			var oldValue = $subprojectField.val( );
			var options = $.map( $selected.attr( 'data-subprojects' ).split( ' ' ), function( el ) {
				var option = document.createElement( 'option' );
				option.appendChild( document.createTextNode( el ) );
				option.setAttribute( 'value', el );
				if ( oldValue === el ) {
					option.setAttribute( 'selected', 'selected' );
				}
				return option;
			} );
			$subprojectField.show( ).empty( ).append( options );
		} else {
			$subprojectField.hide( );
		}
	}

	$( function () {
		var $projectField = $( '#mw-import-table-interwiki #interwiki' );
		if ( $projectField.length ) {
			$projectField.change( updateImportSubprojectList );
			updateImportSubprojectList( );
		}
	} );
}( jQuery ) );
