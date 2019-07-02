( function () {
	'use strict';

	$( function () {
		var $inputs = $( '#mw-specialmute-form input[type="checkbox"]' ),
			saveButton, $saveButton = $( '#save' );

		function isFormChanged() {
			return $inputs.is( function () {
				return this.checked !== this.defaultChecked;
			} );
		}

		if ( $saveButton.length ) {
			saveButton = OO.ui.infuse( $saveButton );
			saveButton.setDisabled( !isFormChanged() );

			$inputs.on( 'change', function () {
				saveButton.setDisabled( !isFormChanged() );
			} );
		}
	} );
}() );
