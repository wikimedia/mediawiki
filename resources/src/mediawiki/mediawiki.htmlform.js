/**
 * Utility functions for jazzing up HTMLForm elements.
 *
 * @class jQuery.plugin.htmlform
 */
( function ( mw, $ ) {

	var cloneCounter = 0;

	/**
	 * Helper function for hide-if to find the nearby form field.
	 *
	 * Find the closest match for the given name, "closest" being the minimum
	 * level of parents to go to find a form field matching the given name or
	 * ending in array keys matching the given name (e.g. "baz" matches
	 * "foo[bar][baz]").
	 *
	 * @private
	 * @param {jQuery} element
	 * @param {string} name
	 * @return {jQuery|null}
	 */
	function hideIfGetField( $el, name ) {
		var $found, $p,
			suffix = name.replace( /^([^\[]+)/, '[$1]' );

		function nameFilter() {
			return this.name === name ||
				( this.name === ( 'wp' + name ) ) ||
				this.name.slice( -suffix.length ) === suffix;
		}

		for ( $p = $el.parent(); $p.length > 0; $p = $p.parent() ) {
			$found = $p.find( '[name]' ).filter( nameFilter );
			if ( $found.length ) {
				return $found;
			}
		}
		return null;
	}

	/**
	 * Helper function for hide-if to return a test function and list of
	 * dependent fields for a hide-if specification.
	 *
	 * @private
	 * @param {jQuery} element
	 * @param {Array} hide-if spec
	 * @return {Array}
	 * @return {jQuery} return.0 Dependent fields
	 * @return {Function} return.1 Test function
	 */
	function hideIfParse( $el, spec ) {
		var op, i, l, v, $field, $fields, fields, func, funcs, getVal;

		op = spec[0];
		l = spec.length;
		switch ( op ) {
			case 'AND':
			case 'OR':
			case 'NAND':
			case 'NOR':
				funcs = [];
				fields = [];
				for ( i = 1; i < l; i++ ) {
					if ( !$.isArray( spec[i] ) ) {
						throw new Error( op + ' parameters must be arrays' );
					}
					v = hideIfParse( $el, spec[i] );
					fields = fields.concat( v[0].toArray() );
					funcs.push( v[1] );
				}
				$fields = $( fields );

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

				if ( $field.first().prop( 'type' ) === 'radio' ||
					$field.first().prop( 'type' ) === 'checkbox'
				) {
					getVal = function () {
						var $selected = $field.filter( ':checked' );
						return $selected.length ? $selected.val() : '';
					};
				} else {
					getVal = function () {
						return $field.val();
					};
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
			return this.show();
		}
		return this.stop( true, true ).fadeIn();
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
			return this.hide();
		}
		return this.stop( true, true ).fadeOut();
	};

	/**
	 * Bind a function to the jQuery object via live(), and also immediately trigger
	 * the function on the objects with an 'instant' parameter set to true.
	 *
	 * @method liveAndTestAtStart
	 * @deprecated since 1.24 Use .on() and .each() directly.
	 * @param {Function} callback
	 * @param {boolean|jQuery.Event} callback.immediate True when the event is called immediately,
	 *  an event object when triggered from an event.
	 * @return jQuery
	 * @chainable
	 */
	mw.log.deprecate( $.fn, 'liveAndTestAtStart', function ( callback ) {
		this
			// Can't really migrate to .on() generically, needs knowledge of
			// calling code to know the correct selector. Fix callers and
			// get rid of this .liveAndTestAtStart() hack.
			.live( 'change', callback )
			.each( function () {
				callback.call( this, true );
			} );
	} );

	function enhance( $root ) {
		var $matrixTooltips, $autocomplete,
			// cache the separator to avoid object creation on each keypress
			colonSeparator = mw.message( 'colon-separator' ).text();

		/**
		 * @ignore
		 * @param {boolean|jQuery.Event} instant
		 */
		function handleSelectOrOther( instant ) {
			var $other = $root.find( '#' + $( this ).attr( 'id' ) + '-other' );
			$other = $other.add( $other.siblings( 'br' ) );
			if ( $( this ).val() === 'other' ) {
				$other.goIn( instant );
			} else {
				$other.goOut( instant );
			}
		}

		// Animate the SelectOrOther fields, to only show the text field when
		// 'other' is selected.
		$root
			.on( 'change', '.mw-htmlform-select-or-other', handleSelectOrOther )
			.each( function () {
				handleSelectOrOther.call( this, true );
			} );

		// Add a dynamic max length to the reason field of SelectAndOther
		// This checks the length together with the value from the select field
		// When the reason list is changed and the bytelimit is longer than the allowed,
		// nothing is done
		$root
			.find( '.mw-htmlform-select-and-other-field' )
			.each( function () {
				var $this = $( this ),
					// find the reason list
					$reasonList = $root.find( '#' + $this.data( 'id-select' ) ),
					// cache the current selection to avoid expensive lookup
					currentValReasonList = $reasonList.val();

				$reasonList.change( function () {
					currentValReasonList = $reasonList.val();
				} );

				$this.byteLimit( function ( input ) {
					// Should be built the same as in HTMLSelectAndOtherField::loadDataFromRequest
					var comment = currentValReasonList;
					if ( comment === 'other' ) {
						comment = input;
					} else if ( input !== '' ) {
						// Entry from drop down menu + additional comment
						comment += colonSeparator + input;
					}
					return comment;
				} );
			} );

		// Set up hide-if elements
		$root.find( '.mw-htmlform-hide-if' ).each( function () {
			var v, $fields, test, func,
				$el = $( this ),
				spec = $el.data( 'hideIf' );

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
			$fields.on( 'change', func );
			func();
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

		if ( $root.find( '.mw-chosen' ).length ) {
			mw.loader.using( 'jquery.chosen', function () {
				$root.find( '.mw-chosen' ).each( function () {
					var type = this.nodeName.toLowerCase(),
						$converted = convertCheckboxesToMulti( $( this ), type );
					$converted.find( '.htmlform-chzn-select' ).chosen( { width: 'auto' } );
				} );
			} );
		}

		$matrixTooltips = $root.find( '.mw-htmlform-matrix .mw-htmlform-tooltip' );
		if ( $matrixTooltips.length ) {
			mw.loader.using( 'jquery.tipsy', function () {
				$matrixTooltips.tipsy( { gravity: 's' } );
			} );
		}

		// Set up autocomplete fields
		$autocomplete = $root.find( '.mw-htmlform-autocomplete' );
		if ( $autocomplete.length ) {
			mw.loader.using( 'jquery.suggestions', function () {
				$autocomplete.suggestions( {
					fetch: function ( val ) {
						var $el = $( this );
						$el.suggestions( 'suggestions',
							$.grep( $el.data( 'autocomplete' ), function ( v ) {
								return v.indexOf( val ) === 0;
							} )
						);
					}
				} );
			} );
		}

		// Add/remove cloner clones without having to resubmit the form
		$root.find( '.mw-htmlform-cloner-delete-button' ).filter( ':input' ).click( function ( ev ) {
			ev.preventDefault();
			$( this ).closest( 'li.mw-htmlform-cloner-li' ).remove();
		} );

		$root.find( '.mw-htmlform-cloner-create-button' ).filter( ':input' ).click( function ( ev ) {
			var $ul, $li, html;

			ev.preventDefault();

			$ul = $( this ).prev( 'ul.mw-htmlform-cloner-ul' );

			html = $ul.data( 'template' ).replace(
				new RegExp( $.escapeRE( $ul.data( 'uniqueId' ) ), 'g' ),
				'clone' + ( ++cloneCounter )
			);

			$li = $( '<li>' )
				.addClass( 'mw-htmlform-cloner-li' )
				.html( html )
				.appendTo( $ul );

			enhance( $li );
		} );

		mw.hook( 'htmlform.enhance' ).fire( $root );

	}

	$( function () {
		enhance( $( document ) );
	} );

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.htmlform
	 */
}( mediaWiki, jQuery ) );
