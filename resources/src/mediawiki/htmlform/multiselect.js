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

	function convertCheckboxesWidgetToCapsules( fieldLayout ) {
		var checkboxesWidget, checkboxesOptions, capsulesOptions, capsulesWidget;

		checkboxesWidget = fieldLayout.fieldWidget;
		checkboxesOptions = checkboxesWidget.checkboxMultiselectWidget.getItems();
		capsulesOptions = checkboxesOptions.map( function ( option ) {
			return new OO.ui.MenuOptionWidget( {
				data: option.getData(),
				label: option.getLabel()
			} );
		} );
		capsulesWidget = new OO.ui.CapsuleMultiselectWidget( {
			menu: {
				items: capsulesOptions
			}
		} );
		capsulesWidget.setItemsFromData( checkboxesWidget.getValue() );

		// Data from CapsuleMultiselectWidget will not be submitted with the form, so keep the original
		// CheckboxMultiselectInputWidget up-to-date.
		capsulesWidget.on( 'change', function () {
			checkboxesWidget.setValue( capsulesWidget.getItemsData() );
		} );

		// Hide original widget and add new one in its place. This is a bit hacky, since the FieldLayout
		// still thinks it's connected to the old widget.
		checkboxesWidget.toggle( false );
		checkboxesWidget.$element.after( capsulesWidget.$element );
	}

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $dropdowns = $root.find( '.mw-htmlform-field-HTMLMultiSelectField.mw-htmlform-dropdown' );
		if ( $dropdowns.length ) {
			$dropdowns.each( function () {
				var $el = $( this ),
					data, modules, extraModules;
				if ( $el.is( '[data-ooui]' ) ) {
					// Load 'oojs-ui-widgets' for CapsuleMultiselectWidget
					modules = [ 'mediawiki.htmlform.ooui', 'oojs-ui-widgets' ];
					data = $el.data( 'mw-modules' );
					if ( data ) {
						// We can trust this value, 'data-mw-*' attributes are banned from user content in Sanitizer
						extraModules = data.split( ',' );
						modules.push.apply( modules, extraModules );
					}
					mw.loader.using( modules, function () {
						/*jshint -W024*/
						convertCheckboxesWidgetToCapsules( OO.ui.FieldLayout.static.infuse( $el ) );
					} );
				} else {
					mw.loader.using( 'jquery.chosen', function () {
						var type = $el.is( 'tr' ) ? 'tr' : 'div',
							$converted = convertCheckboxesToMulti( $el, type );
						$converted.find( '.htmlform-chzn-select' ).chosen( { width: 'auto' } );
					} );
				}
			} );
		}
	} );

}( mediaWiki, jQuery ) );
