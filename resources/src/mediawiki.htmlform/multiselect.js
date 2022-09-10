/*
 * HTMLForm enhancements:
 * Convert multiselect fields from checkboxes to Chosen selector when requested.
 */
( function () {

	function addMulti( $oldContainer, $container ) {
		var name = $oldContainer.find( 'input:first-child' ).attr( 'name' ),
			oldClass = ( ' ' + $oldContainer.attr( 'class' ) + ' ' ).replace( /(mw-htmlform-field-[A-Za-z]+|mw-htmlform-dropdown)/g, '' ),
			$select = $( '<select>' ),
			dataPlaceholder = mw.message( 'htmlform-chosen-placeholder' );
		oldClass = oldClass.trim();
		$select.attr( {
			name: name,
			multiple: 'multiple',
			'data-placeholder': dataPlaceholder.plain(),
			class: 'htmlform-chzn-select mw-input ' + oldClass
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

	function convertCheckboxesWidgetToTags( fieldLayout ) {
		var checkboxesWidget = fieldLayout.fieldWidget;
		var checkboxesOptions = checkboxesWidget.checkboxMultiselectWidget.getItems();
		var menuTagOptions = checkboxesOptions.map( function ( option ) {
			return new OO.ui.MenuOptionWidget( {
				data: option.getData(),
				label: option.getLabel(),
				disabled: option.disabled // Don't take the state of parent elements into account.
			} );
		} );
		var fieldData = checkboxesWidget.data || {};
		var menuTagWidget = new OO.ui.MenuTagMultiselectWidget( {
			$overlay: true,
			menu: {
				items: menuTagOptions
			},
			disabled: checkboxesWidget.isDisabled(),
			placeholder: fieldData.placeholder || ''
		} );
		menuTagWidget.setValue( checkboxesWidget.getValue() );

		menuTagOptions.forEach( function ( option ) {
			if ( option.disabled ) {
				var tagItem = menuTagWidget.findItemFromData( option.getData() );
				// When this disabled option is selected by default.
				if ( tagItem ) {
					tagItem.setFixed( true );
				}
			}
		} );

		// Data from TagMultiselectWidget will not be submitted with the form, so keep the original
		// CheckboxMultiselectInputWidget up-to-date.
		menuTagWidget.on( 'change', function () {
			checkboxesWidget.setValue( menuTagWidget.getValue() );
		} );
		// Synchronize the disable state for submission, and set the proper state of the label.
		menuTagWidget.on( 'disable', function ( isDisabled ) {
			checkboxesWidget.setDisabled( isDisabled );
		} );
		// Change the connected fieldWidget to the new one, so other scripts can infuse the layout
		// and make changes to this widget.
		fieldLayout.fieldWidget = menuTagWidget;

		// Hide original widget and add new one in its place.
		checkboxesWidget.toggle( false );
		checkboxesWidget.$element.after( menuTagWidget.$element );
	}

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $dropdowns = $root.find( '.mw-htmlform-dropdown:not(.oo-ui-widget)' );
		if ( $dropdowns.length ) {
			$dropdowns.each( function () {
				var $el = $( this ),
					data, modules, extraModules;
				if ( $el.is( '[data-ooui]' ) ) {
					// Avoid kicks in multiple times and causing a mess
					if ( $el.find( '.oo-ui-menuTagMultiselectWidget' ).length ) {
						return;
					}
					// Load 'oojs-ui-widgets' for TagMultiselectWidget
					modules = [ 'mediawiki.htmlform.ooui', 'oojs-ui-widgets' ];
					data = $el.data( 'mw-modules' );
					if ( data ) {
						// We can trust this value, 'data-mw-*' attributes are banned from user content in Sanitizer
						extraModules = data.split( ',' );
						modules.push.apply( modules, extraModules );
					}
					mw.loader.using( modules, function () {
						convertCheckboxesWidgetToTags( OO.ui.FieldLayout.static.infuse( $el ) );
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

}() );
