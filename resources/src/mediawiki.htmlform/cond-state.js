/*
 * HTMLForm enhancements:
 * Set up 'hide-if' and 'disable-if' behaviors for form fields that have them.
 */
( function () {

	/**
	 * Helper function for conditional states to find the nearby form field.
	 *
	 * Find the closest match for the given name, "closest" being the minimum
	 * level of parents to go to find a form field matching the given name or
	 * ending in array keys matching the given name (e.g. "baz" matches
	 * "foo[bar][baz]").
	 *
	 * @ignore
	 * @private
	 * @param {jQuery} $root
	 * @param {string} name
	 * @return {jQuery|OO.ui.Widget|null}
	 */
	function conditionGetField( $root, name ) {
		var nameFilter = function () {
			return this.name === name;
		};
		var $found = $root.find( '[name]' ).filter( nameFilter );
		if ( !$found.length ) {
			// Field cloner can load from template dynamically and fire event on sub element
			$found = $root.closest( 'form' ).find( '[name]' ).filter( nameFilter );
		}
		if ( $found.length ) {
			var $widget = $found.closest( '.oo-ui-widget[data-ooui]' );
			if ( $widget.length ) {
				return OO.ui.Widget.static.infuse( $widget );
			}
			return $found;
		}
		return null;
	}

	/**
	 * Helper function for conditional states to return a test function and list of
	 * dependent fields for a conditional states specification.
	 *
	 * @ignore
	 * @private
	 * @param {jQuery} $root
	 * @param {Array} spec
	 * @return {Array}
	 * @return {Array} return.0 Dependent fields, array of jQuery objects or OO.ui.Widgets
	 * @return {Function} return.1 Test function
	 */
	function conditionParse( $root, spec ) {
		var op, i, l, v, field, $field, fields, func, funcs, getVal;

		op = spec[ 0 ];
		l = spec.length;
		switch ( op ) {
			case 'AND':
			case 'OR':
			case 'NAND':
			case 'NOR':
				funcs = [];
				fields = [];
				for ( i = 1; i < l; i++ ) {
					if ( !Array.isArray( spec[ i ] ) ) {
						throw new Error( op + ' parameters must be arrays' );
					}
					v = conditionParse( $root, spec[ i ] );
					fields = fields.concat( v[ 0 ] );
					funcs.push( v[ 1 ] );
				}

				l = funcs.length;
				var valueChk = { AND: false, OR: true, NAND: false, NOR: true };
				var valueRet = { AND: true, OR: false, NAND: false, NOR: true };
				func = function () {
					var j;
					for ( j = 0; j < l; j++ ) {
						if ( valueChk[ op ] === funcs[ j ]() ) {
							return !valueRet[ op ];
						}
					}
					return valueRet[ op ];
				};

				return [ fields, func ];

			case 'NOT':
				if ( l !== 2 ) {
					throw new Error( 'NOT takes exactly one parameter' );
				}
				if ( !Array.isArray( spec[ 1 ] ) ) {
					throw new Error( 'NOT parameters must be arrays' );
				}
				v = conditionParse( $root, spec[ 1 ] );
				fields = v[ 0 ];
				func = v[ 1 ];
				return [ fields, function () {
					return !func();
				} ];

			case '===':
			case '!==':
				if ( l !== 3 ) {
					throw new Error( op + ' takes exactly two parameters' );
				}
				field = conditionGetField( $root, spec[ 1 ] );
				if ( !field ) {
					return [ [], function () {
						return false;
					} ];
				}
				v = spec[ 2 ];

				if ( !( field instanceof $ ) ) {
					// field is a OO.ui.Widget
					if ( field.supports( 'isSelected' ) ) {
						getVal = function () {
							var selected = field.isSelected();
							return selected ? field.getValue() : '';
						};
					} else {
						getVal = function () {
							return field.getValue();
						};
					}
				} else {
					$field = $( field );
					if ( $field.prop( 'type' ) === 'radio' || $field.prop( 'type' ) === 'checkbox' ) {
						getVal = function () {
							var $selected = $field.filter( ':checked' );
							return $selected.length ? $selected.val() : '';
						};
					} else {
						getVal = function () {
							return $field.val();
						};
					}
				}

				switch ( op ) {
					case '===':
						func = function () {
							return getVal() === v;
						};
						break;
					case '!==':
						func = function () {
							return getVal() !== v;
						};
						break;
				}

				return [ [ field ], func ];

			default:
				throw new Error( 'Unrecognized operation \'' + op + '\'' );
		}
	}

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $exclude = $root.find( '.mw-htmlform-autoinfuse-lazy' )
			.find( '.mw-htmlform-hide-if, .mw-htmlform-disable-if' );
		var $fields = $root.find( '.mw-htmlform-hide-if, .mw-htmlform-disable-if' ).not( $exclude );
		var $oouiFields = $fields.filter( '[data-ooui]' );
		var modules = [];

		if ( $oouiFields.length ) {
			modules.push( 'mediawiki.htmlform.ooui' );
			$oouiFields.each( function () {
				var data, extraModules,
					$el = $( this );

				data = $el.data( 'mw-modules' );
				if ( data ) {
					// We can trust this value, 'data-mw-*' attributes are banned from user content in Sanitizer
					extraModules = data.split( ',' );
					modules.push.apply( modules, extraModules );
				}
			} );
		}

		mw.loader.using( modules ).done( function () {
			$fields.each( function () {
				var v, i, fields = [], test = {}, func, spec, $elOrLayout,
					$el = $( this );

				if ( $el.is( '[data-ooui]' ) ) {
					// $elOrLayout should be a FieldLayout that mixes in mw.htmlform.Element
					$elOrLayout = OO.ui.FieldLayout.static.infuse( $el );
					spec = $elOrLayout.condState;
				} else {
					$elOrLayout = $el;
					spec = $el.data( 'condState' );
				}

				if ( !spec ) {
					return;
				}

				[ 'hide', 'disable' ].forEach( function ( type ) {
					if ( spec[ type ] ) {
						v = conditionParse( $root, spec[ type ] );
						fields = fields.concat( fields, v[ 0 ] );
						test[ type ] = v[ 1 ];
					}
				} );
				func = function () {
					var shouldHide = spec.hide ? test.hide() : false;
					var shouldDisable = shouldHide || ( spec.disable ? test.disable() : false );
					if ( spec.hide ) {
						// The .toggle() method works mostly the same for jQuery objects and OO.ui.Widget
						$elOrLayout.toggle( !shouldHide );
					}

					// Disable fields with either 'disable-if' or 'hide-if' rules
					// Hidden fields should be disabled to avoid users meet validation failure on these fields,
					// because disabled fields will not be submitted with the form.
					if ( $elOrLayout instanceof $ ) {
						// This also finds elements inside any nested fields (in case of HTMLFormFieldCloner),
						// which is problematic. But it works because:
						// * HTMLFormFieldCloner::createFieldsForKey() copies '*-if' rules to nested fields
						// * jQuery collections like $fields are in document order, so we register event
						//   handlers for parents first
						// * Event handlers are fired in the order they were registered, so even if the handler
						//   for parent messed up the child, the handle for child will run next and fix it
						$elOrLayout.find( 'input, textarea, select' ).each( function () {
							var $this = $( this );
							if ( shouldDisable ) {
								if ( $this.data( 'was-disabled' ) === undefined ) {
									$this.data( 'was-disabled', $this.prop( 'disabled' ) );
								}
								$this.prop( 'disabled', true );
							} else {
								$this.prop( 'disabled', $this.data( 'was-disabled' ) );
							}
						} );
					} else {
						// $elOrLayout is a OO.ui.FieldLayout
						if ( shouldDisable ) {
							if ( $elOrLayout.wasDisabled === undefined ) {
								$elOrLayout.wasDisabled = $elOrLayout.fieldWidget.isDisabled();
							}
							$elOrLayout.fieldWidget.setDisabled( true );
						} else if ( $elOrLayout.wasDisabled !== undefined ) {
							$elOrLayout.fieldWidget.setDisabled( $elOrLayout.wasDisabled );
						}
					}
				};
				for ( i = 0; i < fields.length; i++ ) {
					// The .on() method works mostly the same for jQuery objects and OO.ui.Widget
					fields[ i ].on( 'change', func );
				}
				func();
			} );
		} );
	} );

}() );
