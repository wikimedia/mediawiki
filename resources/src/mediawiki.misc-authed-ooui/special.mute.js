( function () {
	'use strict';

	$( () => {
		const $inputs = $( '#mw-specialmute-form input[type="checkbox"]' ),
			$saveButton = $( '#save' );

		function isFormChanged() {
			return $inputs.is( function () {
				return this.checked !== this.defaultChecked;
			} );
		}

		if ( $saveButton.length ) {
			const saveButton = OO.ui.infuse( $saveButton );
			saveButton.setDisabled( !isFormChanged() );

			$inputs.on( 'change', () => {
				saveButton.setDisabled( !isFormChanged() );
			} );
		}
	} );
}() );
