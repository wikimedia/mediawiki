/**
 * Utility functions for jazzing up HTMLForm elements.
 *
 * @class jQuery.plugin.htmlform
 */
( function ( mw, $ ) {

	var cloneCounter = 0,
		hideIfTests = {
			'===': function ( a, b ) {
				return a === b;
			},
			'!==': function ( a, b ) {
				return a !== b;
			}
		};

	/**
	 * jQuery plugin to fade or snap to visible state.
	 *
	 * @param {boolean} [instantToggle=false]
	 * @return {jQuery}
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

	function enhance( $root ) {

		// Animate the SelectOrOther fields, to only show the text field when
		// 'other' is selected.
		$root.find( '.mw-htmlform-select-or-other' ).liveAndTestAtStart( function ( instant ) {
			var $other = $root.find( '#' + $( this ).attr( 'id' ) + '-other' );
			$other = $other.add( $other.siblings( 'br' ) );
			if ( $( this ).val() === 'other' ) {
				$other.goIn( instant );
			} else {
				$other.goOut( instant );
			}
		} );

		// Set up hide-if elements
		$root.find( '.mw-htmlform-hide-if' ).each( function () {
			var $el = $( this ),
				data = $el.data( 'hideIf' ),
				sel, value, test, func, $found, $p;

			if ( !data ) {
				return;
			}

			value = data[2];
			test = hideIfTests[data[0]];

			if ( !test ) {
				return;
			}
			func = function () {
				if ( test( $( this ).val(), value ) ) {
					$el.hide();
				} else {
					$el.show();
				}
			};

			// Find the closest match for the given name, "closest" being the
			// minimum level of parents to go to find a form field matching the
			// given name or ending in array keys matching the given name
			// (e.g. "baz" matches "foo[bar][baz]").
			sel = '[name="' + data[1] + '"],' +
				'[name="wp' + data[1] + '"],' +
				'[name$="' + data[1].replace( /^([^\[]+)/, '[$1]' ) + '"]';
			$found = $();
			for ( $p = $el.parent(); $p.length > 0; $p = $p.parent() ) {
				$found = $p.find( sel );
				if ( $found.length > 0 ) {
					$found.first().change( func );
					func.call( $found.first() );
					break;
				}
			}
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

		var $matrixTooltips = $root.find( '.mw-htmlform-matrix .mw-htmlform-tooltip' );
		if ( $matrixTooltips.length ) {
			mw.loader.using( 'jquery.tipsy', function () {
				$matrixTooltips.tipsy( { gravity: 's' } );
			} );
		}

		( function () {
			var inputs, i;

			inputs = $( 'input[type=date]' );
			if ( inputs.length === 0 ) {
				return;
			}

			// Assume that if the browser implements validation for <input type=date>
			// (so it rejects "bogus" as a value) then it supports a date picker too.
			i = document.createElement( 'input' );
			i.setAttribute( 'type', 'date' );
			i.value = 'bogus';
			if ( i.value === 'bogus' ) {
				mw.loader.using( 'jquery.ui.datepicker', function () {
					inputs.each( function () {
						var i = $( this );
						// Reset the type, Just In Case
						i.prop( 'type', 'text' );
						i.datepicker( {
							dateFormat: 'yy-mm-dd',
							constrainInput: true,
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							showButtonPanel: true,
							minDate: i.data( 'min' ),
							maxDate: i.data( 'max' ),
						} );
					} );
				} );
			}
		}() );

		// Add/remove cloner clones without having to resubmit the form
		$root.find( '.mw-htmlform-cloner-delete-button' ).click( function ( ev ) {
			ev.preventDefault();
			$( this ).closest( 'li.mw-htmlform-cloner-li' ).remove();
		} );

		$root.find( '.mw-htmlform-cloner-create-button' ).click( function ( ev ) {
			var $ul, $li, html;

			ev.preventDefault();

			$ul = $( this ).prev( 'ul.mw-htmlform-cloner-ul' );

			html = $ul.data( 'template' ).replace(
				$ul.data( 'uniqueId' ), 'clone' + ( ++cloneCounter ), 'g'
			);

			$li = $( '<li>' )
				.addClass( 'mw-htmlform-cloner-li' )
				.html( html )
				.appendTo( $ul );

			enhance( $li );
		} );

	}

	$( function () {
		enhance( $( document ) );
	} );

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.htmlform
	 */
}( mediaWiki, jQuery ) );
