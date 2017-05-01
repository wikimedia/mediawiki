/*
 * HTMLForm enhancements:
 * Set up 'hide-if' behaviors for form fields that have them.
 */
( function ( mw, $ ) {

	/*jshint -W024*/

	/**
	 * Helper function for hide-if to find the nearby form field.
	 *
	 * Find the closest match for the given name, "closest" being the minimum
	 * level of parents to go to find a form field matching the given name or
	 * ending in array keys matching the given name (e.g. "baz" matches
	 * "foo[bar][baz]").
	 *
	 * @ignore
	 * @private
	 * @param {jQuery} $el
	 * @param {string} name
	 * @return {jQuery|OO.ui.Widget|null}
	 */
	function hideIfGetField( $el, name ) {
		var $found, $p, $widget,
			suffix = name.replace( /^([^\[]+)/, '[$1]' );

		function nameFilter() {
			return this.name === name ||
				( this.name === ( 'wp' + name ) ) ||
				this.name.slice( -suffix.length ) === suffix;
		}

		for ( $p = $el.parent(); $p.length > 0; $p = $p.parent() ) {
			$found = $p.find( '[name]' ).filter( nameFilter );
			if ( $found.length ) {
				$widget = $found.closest( '.oo-ui-widget[data-ooui]' );
				if ( $widget.length ) {
					return OO.ui.Widget.static.infuse( $widget );
				}
				return $found;
			}
		}
		return null;
	}

	/**
	 * Helper function for hide-if to return a test function and list of
	 * dependent fields for a hide-if specification.
	 *
	 * @ignore
	 * @private
	 * @param {jQuery} $el
	 * @param {Array} spec
	 * @return {Array}
	 * @return {Array} return.0 Dependent fields, array of jQuery objects or OO.ui.Widgets
	 * @return {Function} return.1 Test function
	 */
	function hideIfParse( $el, spec ) {
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
					if ( !$.isArray( spec[ i ] ) ) {
						throw new Error( op + ' parameters must be arrays' );
					}
					v = hideIfParse( $el, spec[ i ] );
					fields = fields.concat( v[ 0 ] );
					funcs.push( v[ 1 ] );
				}

				l = funcs.length;
				switch ( op ) {
					case 'AND':
						func = function () {
							var i;
							for ( i = 0; i < l; i++ ) {
								if ( !funcs[ i ]() ) {
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
								if ( funcs[ i ]() ) {
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
								if ( !funcs[ i ]() ) {
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
								if ( funcs[ i ]() ) {
									return false;
								}
							}
							return true;
						};
						break;
				}

				return [ fields, func ];

			case 'NOT':
				if ( l !== 2 ) {
					throw new Error( 'NOT takes exactly one parameter' );
				}
				if ( !$.isArray( spec[ 1 ] ) ) {
					throw new Error( 'NOT parameters must be arrays' );
				}
				v = hideIfParse( $el, spec[ 1 ] );
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
				field = hideIfGetField( $el, spec[ 1 ] );
				if ( !field ) {
					return [ [], function () {
						return false;
					} ];
				}
				v = spec[ 2 ];

				if ( !( field instanceof jQuery ) ) {
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
		$root.find( '.mw-htmlform-hide-if' ).each( function () {
			var v, i, fields, test, func, spec, self, modules, data,extraModules,
				$el = $( this );

			modules = [];
			if ( $el.is( '[data-ooui]' ) ) {
				modules.push( 'mediawiki.htmlform.ooui' );
				data = $el.data( 'mw-modules' );
				if ( data ) {
					// We can trust this value, 'data-mw-*' attributes are banned from user content in Sanitizer
					extraModules = data.split( ',' );
					modules.push.apply( modules, extraModules );
				}
			}

			mw.loader.using( modules ).done( function () {
				if ( $el.is( '[data-ooui]' ) ) {
					// self should be a FieldLayout that mixes in mw.htmlform.Element
					self = OO.ui.FieldLayout.static.infuse( $el );
					spec = self.hideIf;
					// The original element has been replaced with infused one
					$el = self.$element;
				} else {
					self = $el;
					spec = $el.data( 'hideIf' );
				}

				if ( !spec ) {
					return;
				}

				v = hideIfParse( $el, spec );
				fields = v[ 0 ];
				test = v[ 1 ];
				// The .toggle() method works mostly the same for jQuery objects and OO.ui.Widget
				func = function () {
					self.toggle( !test() );
				};
				for ( i = 0; i < fields.length; i++ ) {
					// The .on() method works mostly the same for jQuery objects and OO.ui.Widget
					fields[ i ].on( 'change', func );
				}
				func();
			} );
		} );
	} );

}( mediaWiki, jQuery ) );
