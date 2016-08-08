/*
 * HTMLForm enhancements:
 * Convert multiselect fields from checkboxes to Chosen selector when requested.
 */
( function ( mw, $ ) {

	function addMulti( $oldContainer, $container ) {
		var name = $oldContainer.find( 'input:first-child' ).attr( 'name' ),
			oldClass = ( ' ' + $oldContainer.attr( 'class' ) + ' ' ).replace( /(mw-htmlform-field-HTMLMultiSelectField|mw-chosen|mw-htmlform-dropdown)/g, '' ),
			$select = $( '<select>' ),
			dataPlaceholder = mw.message( 'htmlform-chosen-placeholder' );
		oldClass = $.trim( oldClass );
		$select.attr( {
			name: name,
			multiple: 'multiple',
			'data-placeholder': dataPlaceholder.plain(),
			'class': 'htmlform-chzn-select mw-input ' + oldClass
		} );
		$oldContainer.find( 'input' ).each( function () {
			var $oldInput = $( this ),
			checked = $oldInput.prop( 'checked' ),
			$option = $( '<option>' );
			$option.prop( 'value', $oldInput.prop( 'value' ) );
			if ( checked ) {
				$option.prop( 'selected', true );
			}
			$option.text( $oldInput.prop( 'value' ) );
			$select.append( $option );
		} );
		$container.append( $select );
	}

	function convertCheckboxesToMulti( $oldContainer, type ) {
		var $fieldLabel = $( '<td>' ),
		$td = $( '<td>' ),
		$fieldLabelText = $( '<label>' ),
		$container;
		if ( type === 'tr' ) {
			addMulti( $oldContainer, $td );
			$container = $( '<tr>' );
			$container.append( $td );
		} else if ( type === 'div' ) {
			$fieldLabel = $( '<div>' );
			$container = $( '<div>' );
			addMulti( $oldContainer, $container );
		}
		$fieldLabel.attr( 'class', 'mw-label' );
		$fieldLabelText.text( $oldContainer.find( '.mw-label label' ).text() );
		$fieldLabel.append( $fieldLabelText );
		$container.prepend( $fieldLabel );
		$oldContainer.replaceWith( $container );
		return $container;
	}

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		if ( $root.find( '.mw-htmlform-dropdown' ).length ) {
			mw.loader.using( 'jquery.chosen', function () {
				$root.find( '.mw-htmlform-dropdown' ).each( function () {
					var type = this.nodeName.toLowerCase(),
						$converted = convertCheckboxesToMulti( $( this ), type );
					$converted.find( '.htmlform-chzn-select' ).chosen( { width: 'auto' } );
				} );
			} );
		}
	} );

}( mediaWiki, jQuery ) );
