/**
 * This plugin provides functionallity to expand a text box on focus to double it's current width
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
 *
 */
( function( $ ) {

$.expandableField = {
	/**
	 * Expand the field, make the callback
	 */
	expandField: function( e, context ) {
		context.config.beforeExpand.call( context.data.$field, context );
		context.data.$field
			.animate( { 'width': context.data.expandedWidth }, 'fast', function() {
				context.config.afterExpand.call( this, context );
			} );
	},
	/**
	 * Condense the field, make the callback
	 */
	condenseField: function( e, context ) {
		context.config.beforeCondense.call( context.data.$field, context );
		context.data.$field
			.animate( { 'width': context.data.condensedWidth }, 'fast', function() {
				context.config.afterCondense.call( this, context );
			} );
	},
	/**
	 * Sets the value of a property, and updates the widget accordingly
	 * @param {String} property Name of property
	 * @param {Mixed} value Value to set property with
	 */
	configure: function( context, property, value ) {
		// Validate creation using fallback values
		switch( property ) {
			default:
				context.config[property] = value;
				break;
		}
	}

};
$.fn.expandableField = function() {
	
	// Multi-context fields
	var returnValue = null;
	var args = arguments;
	
	$( this ).each( function() {

		/* Construction / Loading */
		
		var context = $( this ).data( 'expandableField-context' );
		if ( context == null ) {
			context = {
				config: {
					// callback function for before collapse
					'beforeCondense': function( context ) {},
					// callback function for before expand
					'beforeExpand': function( context ) {},
					// callback function for after collapse
					'afterCondense': function( context ) {},
					// callback function for after expand
					'afterExpand': function( context ) {},
					// Whether the field should expand to the left or the right -- defaults to left
					'expandToLeft': true
				}
			};
		}
		
		/* API */
		// Handle various calling styles
		if ( args.length > 0 ) {
			if ( typeof args[0] == 'object' ) {
				// Apply set of properties
				for ( var key in args[0] ) {
					$.expandableField.configure( context, key, args[0][key] );
				}
			} else if ( typeof args[0] == 'string' ) {
				if ( args.length > 1 ) {
					// Set property values
					$.expandableField.configure( context, args[0], args[1] );
				} else if ( returnValue == null ) {
					// Get property values, but don't give access to internal data - returns only the first
					returnValue = ( args[0] in context.config ? undefined : context.config[args[0]] );
				}
			}
		}
		
		/* Initialization */
		
		if ( typeof context.data == 'undefined' ) {
			context.data = {
				// The width of the field in it's condensed state
				'condensedWidth': $( this ).width(),
				// The width of the field in it's expanded state
				'expandedWidth': $( this ).width() * 2,
				// Reference to the field
				'$field': $( this )
			};
			
			$( this )
				.addClass( 'expandableField' )
				.focus( function( e ) {
					$.expandableField.expandField( e, context );
				} )
				.delayedBind( 250, 'blur', function( e ) {
					$.expandableField.condenseField( e, context );
				} );
		}
		// Store the context for next time
		$( this ).data( 'expandableField-context', context );
	} );
	return returnValue !== null ? returnValue : $(this);
};

} )( jQuery );
