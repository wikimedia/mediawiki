/**
 * This plugin provides functionality to expand a text box on focus to double it's current width
 *
 * Usage:
 *
 * Set options:
 *		$('#textbox').expandableField( { option1: value1, option2: value2 } );
 *		$('#textbox').expandableField( option, value );
 * Get option:
 *		value = $('#textbox').expandableField( option );
 * Initialize:
 *		$('#textbox').expandableField();
 *
 * Options:
 */
( function ( $ ) {

	$.expandableField = {
		/**
		 * Expand the field, make the callback
		 *
		 * @param {jQuery.Event} e Event
		 * @param {Object} context
		 */
		expandField: function ( e, context ) {
			context.config.beforeExpand.call( context.data.$field, context );
			context.data.$field
				.animate( { width: context.data.expandedWidth }, 'fast', function () {
					context.config.afterExpand.call( this, context );
				} );
		},
		/**
		 * Condense the field, make the callback
		 *
		 * @param {jQuery.Event} e Event
		 * @param {Object} context
		 */
		condenseField: function ( e, context ) {
			context.config.beforeCondense.call( context.data.$field, context );
			context.data.$field
				.animate( { width: context.data.condensedWidth }, 'fast', function () {
					context.config.afterCondense.call( this, context );
				} );
		},
		/**
		 * Sets the value of a property, and updates the widget accordingly
		 *
		 * @param {Object} context
		 * @param {string} property Name of property
		 * @param {Mixed} value Value to set property with
		 */
		configure: function ( context, property, value ) {
			// TODO: Validate creation using fallback values
			context.config[ property ] = value;
		}

	};

	$.fn.expandableField = function () {

		// Multi-context fields
		var returnValue,
			args = arguments;

		$( this ).each( function () {
			var key, context, timeout;

			/* Construction / Loading */

			context = $( this ).data( 'expandableField-context' );

			// TODO: Do we need to check both null and undefined?
			if ( context === undefined || context === null ) {
				context = {
					config: {
						// callback function for before collapse
						beforeCondense: function () {},

						// callback function for before expand
						beforeExpand: function () {},

						// callback function for after collapse
						afterCondense: function () {},

						// callback function for after expand
						afterExpand: function () {},

						// Whether the field should expand to the left or the right -- defaults to left
						expandToLeft: true
					}
				};
			}

			/* API */
			// Handle various calling styles
			if ( args.length > 0 ) {
				if ( typeof args[ 0 ] === 'object' ) {
					// Apply set of properties
					for ( key in args[ 0 ] ) {
						$.expandableField.configure( context, key, args[ 0 ][ key ] );
					}
				} else if ( typeof args[ 0 ] === 'string' ) {
					if ( args.length > 1 ) {
						// Set property values
						$.expandableField.configure( context, args[ 0 ], args[ 1 ] );

					// TODO: Do we need to check both null and undefined?
					} else if ( returnValue === null || returnValue === undefined ) {
						// Get property values, but don't give access to internal data - returns only the first
						returnValue = ( args[ 0 ] in context.config ? undefined : context.config[ args[ 0 ] ] );
					}
				}
			}

			/* Initialization */

			if ( context.data === undefined ) {
				context.data = {
					// The width of the field in it's condensed state
					condensedWidth: $( this ).width(),

					// The width of the field in it's expanded state
					expandedWidth: $( this ).width() * 2,

					// Reference to the field
					$field: $( this )
				};

				$( this )
					.addClass( 'expandableField' )
					.focus( function ( e ) {
						clearTimeout( timeout );
						$.expandableField.expandField( e, context );
					} )
					.blur( function ( e ) {
						timeout = setTimeout( function () {
							$.expandableField.condenseField( e, context );
						}, 250 );
					} );
			}
			// Store the context for next time
			$( this ).data( 'expandableField-context', context );
		} );
		return returnValue !== undefined ? returnValue : $( this );
	};

}( jQuery ) );
