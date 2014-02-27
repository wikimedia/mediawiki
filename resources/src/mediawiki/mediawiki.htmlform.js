/**
 * Utility functions for jazzing up HTMLForm elements.
 *
 * @class jQuery.plugin.htmlform
 */
( function ( mw, $ ) {

	/**
	 * Helper function for hide-if to find the nearby form field.
	 *
	 * Find the closest match for the given name, "closest" being the minimum
	 * level of parents to go to find a form field matching the given name or
	 * ending in array keys matching the given name (e.g. "baz" matches
	 * "foo[bar][baz]").
	 *
	 * @param {jQuery} element
	 * @param {string} name
	 * @return {jQuery|null}
	 */
	function hideIfGetField( $el, name ) {
		var sel, $found, $p;

		sel = '[name="' + name + '"],' +
			'[name="wp' + name + '"],' +
			'[name$="' + name.replace( /^([^\[]+)/, '[$1]' ) + '"]';
		for ( $p = $el.parent(); $p.length > 0; $p = $p.parent() ) {
			$found = $p.find( sel );
			if ( $found.length > 0 ) {
				return $found;
			}
		}
		return null;
	}

	/**
	 * Helper function for hide-if to return a test function and list of
	 * dependent fields for a hide-if specification.
	 *
	 * @param {jQuery} element
	 * @param {Array} hide-if spec
	 * @return {Array} 2 elements: jQuery of dependent fields, and test function
	 */
	function hideIfParse( $el, spec ) {
		var op, i, l, v, $field, $fields, func, funcs, getVal;

		op = spec[0];
		l = spec.length;
		switch ( op ) {
			case 'AND':
			case 'OR':
			case 'NAND':
			case 'NOR':
				funcs = [];
				$fields = $();
				for ( i = 1; i < l; i++ ) {
					if ( !$.isArray( spec[i] ) ) {
						throw new Error( op + ' parameters must be arrays' );
					}
					v = hideIfParse( $el, spec[i] );
					$fields = $fields.add( v[0] );
					funcs.push( v[1] );
				}

				l = funcs.length;
				switch ( op ) {
					case 'AND':
						func = function () {
							var i;
							for ( i = 0; i < l; i++ ) {
								if ( !funcs[i]() ) {
									return false;
								}
							}
							return true;
						};
						break;

					case 'OR':
						func = function () {
							var i;
							for ( i = 0; i < l; i++ ) {
								if ( funcs[i]() ) {
									return true;
								}
							}
							return false;
						};
						break;

					case 'NAND':
						func = function () {
							var i;
							for ( i = 0; i < l; i++ ) {
								if ( !funcs[i]() ) {
									return true;
								}
							}
							return false;
						};
						break;

					case 'NOR':
						func = function () {
							var i;
							for ( i = 0; i < l; i++ ) {
								if ( funcs[i]() ) {
									return false;
								}
							}
							return true;
						};
						break;
				}

				return [ $fields, func ];

			case 'NOT':
				if ( l !== 2 ) {
					throw new Error( 'NOT takes exactly one parameter' );
				}
				if ( !$.isArray( spec[1] ) ) {
					throw new Error( 'NOT parameters must be arrays' );
				}
				v = hideIfParse( $el, spec[1] );
				$fields = v[0];
				func = v[1];
				return [ $fields, function () {
					return !func();
				} ];

			case '===':
			case '!==':
				if ( l !== 3 ) {
					throw new Error( op + ' takes exactly two parameters' );
				}
				$field = hideIfGetField( $el, spec[1] );
				if ( !$field ) {
					return [ $(), function () {
						return false;
					} ];
				}
				v = spec[2];

				if ( $field.first().attr( 'type' ) === 'radio' ||
					$field.first().attr( 'type' ) === 'checkbox'
				) {
					getVal = function () {
						return $field.filter( ':checked' ).val();
					};
				} else {
					getVal = $field.val;
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

				return [ $field, func ];

			default:
				throw new Error( 'Unrecognized operation \'' + op + '\'' );
		}
	}

	/**
	 * jQuery plugin to fade or snap to visible state.
	 *
	 * @param {boolean} [instantToggle=false]
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.goIn = function ( instantToggle ) {
		if ( instantToggle === true ) {
			return $( this ).show();
		}
		return $( this ).stop( true, true ).fadeIn();
	};

	/**
	 * jQuery plugin to fade or snap to hiding state.
	 *
	 * @param {boolean} [instantToggle=false]
	 * @return jQuery
	 * @chainable
	 */
	$.fn.goOut = function ( instantToggle ) {
		if ( instantToggle === true ) {
			return $( this ).hide();
		}
		return $( this ).stop( true, true ).fadeOut();
	};

	/**
	 * Bind a function to the jQuery object via live(), and also immediately trigger
	 * the function on the objects with an 'instant' parameter set to true.
	 * @param {Function} callback
	 * @param {boolean|jQuery.Event} callback.immediate True when the event is called immediately,
	 *  an event object when triggered from an event.
	 */
	$.fn.liveAndTestAtStart = function ( callback ) {
		$( this )
			.live( 'change', callback )
			.each( function () {
				callback.call( this, true );
			} );
	};

	$( function () {

		// Animate the SelectOrOther fields, to only show the text field when
		// 'other' is selected.
		$( '.mw-htmlform-select-or-other' ).liveAndTestAtStart( function ( instant ) {
			var $other = $( '#' + $( this ).attr( 'id' ) + '-other' );
			$other = $other.add( $other.siblings( 'br' ) );
			if ( $( this ).val() === 'other' ) {
				$other.goIn( instant );
			} else {
				$other.goOut( instant );
			}
		} );

		// Set up hide-if elements
		$( '.mw-htmlform-hide-if' ).each( function () {
			var $el = $( this ),
				spec = $el.data( 'hideIf' ),
				v, $fields, test, func;

			if ( !spec ) {
				return;
			}

			v = hideIfParse( $el, spec );
			$fields = v[0];
			test = v[1];
			func = function () {
				if ( test() ) {
					$el.hide();
				} else {
					$el.show();
				}
			};
			$fields.change( func );
			func();
		} );

	} );

	function addMulti( $oldContainer, $container ) {
		var name = $oldContainer.find( 'input:first-child' ).attr( 'name' ),
			oldClass = ( ' ' + $oldContainer.attr( 'class' ) + ' ' ).replace( /(mw-htmlform-field-HTMLMultiSelectField|mw-chosen)/g, '' ),
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

	if ( $( '.mw-chosen' ).length ) {
		mw.loader.using( 'jquery.chosen', function () {
			$( '.mw-chosen' ).each( function () {
				var type = this.nodeName.toLowerCase(),
					$converted = convertCheckboxesToMulti( $( this ), type );
				$converted.find( '.htmlform-chzn-select' ).chosen( { width: 'auto' } );
			} );
		} );
	}

	$( function () {
		var $matrixTooltips = $( '.mw-htmlform-matrix .mw-htmlform-tooltip' );
		if ( $matrixTooltips.length ) {
			mw.loader.using( 'jquery.tipsy', function () {
				$matrixTooltips.tipsy( { gravity: 's' } );
			} );
		}
	} );

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.htmlform
	 */
}( mediaWiki, jQuery ) );
