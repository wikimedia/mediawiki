/**
 * jQuery Spinner
 *
 * Simple jQuery plugin to create, inject and remove spinners.
 *
 * @class jQuery.plugin.spinner
 */
( function ( $ ) {

	// Default options for new spinners,
	// stored outside the function to share between calls.
	var defaults = {
		id: undefined,
		size: 'small',
		type: 'inline'
	};

	$.extend( {
		/**
		 * Create a spinner element
		 *
		 * The argument is an object with options used to construct the spinner (see below).
		 *
		 * It is a good practice to keep a reference to the created spinner to be able to remove it
		 * later. Alternatively, one can use the 'id' option and #removeSpinner (but make sure to choose
		 * an id that's unlikely to cause conflicts, e.g. with extensions, gadgets or user scripts).
		 *
		 * CSS classes used:
		 *
		 * - .mw-spinner for every spinner
		 * - .mw-spinner-small / .mw-spinner-large for size
		 * - .mw-spinner-block / .mw-spinner-inline for display types
		 *
		 * Example:
		 *
		 *     // Create a large spinner reserving all available horizontal space.
		 *     var $spinner = $.createSpinner( { size: 'large', type: 'block' } );
		 *     // Insert above page content.
		 *     $( '#mw-content-text' ).prepend( $spinner );
		 *
		 *     // Place a small inline spinner next to the "Save" button
		 *     var $spinner = $.createSpinner( { size: 'small', type: 'inline' } );
		 *     // Alternatively, just `$.createSpinner();` as these are the default options.
		 *     $( '#wpSave' ).after( $spinner );
		 *
		 *     // The following two are equivalent:
		 *     $.createSpinner( 'magic' );
		 *     $.createSpinner( { id: 'magic' } );
		 *
		 * @static
		 * @inheritable
		 * @param {Object|string} [opts] Options. If a string is given, it will be treated as the value
		 *   of the `id` option. If an object is given, the possible option keys are:
		 * @param {string} [opts.id] If given, spinner will be given an id of "mw-spinner-{id}".
		 * @param {string} [opts.size='small'] 'small' or 'large' for a 20-pixel or 32-pixel spinner.
		 * @param {string} [opts.type='inline'] 'inline' or 'block'. Inline creates an inline-block with
		 *   width and height equal to spinner size. Block is a block-level element with width 100%,
		 *   height equal to spinner size.
		 * @return {jQuery}
		 */
		createSpinner: function ( opts ) {
			if ( opts !== undefined && $.type( opts ) !== 'object' ) {
				opts = {
					id: opts
				};
			}

			opts = $.extend( {}, defaults, opts );

			var $spinner = $( '<div>', { 'class': 'mw-spinner', 'title': '...' } );
			if ( opts.id !== undefined ) {
				$spinner.attr( 'id', 'mw-spinner-' + opts.id );
			}

			$spinner.addClass( opts.size === 'large' ? 'mw-spinner-large' : 'mw-spinner-small' );
			$spinner.addClass( opts.type === 'block' ? 'mw-spinner-block' : 'mw-spinner-inline' );

			return $spinner;
		},

		/**
		 * Remove a spinner element
		 *
		 * @static
		 * @inheritable
		 * @param {string} id Id of the spinner, as passed to #createSpinner
		 * @return {jQuery} The (now detached) spinner element
		 */
		removeSpinner: function ( id ) {
			return $( '#mw-spinner-' + id ).remove();
		}
	} );

	/**
	 * Inject a spinner after each element in the collection
	 *
	 * Inserts spinner as siblings (not children) of the target elements.
	 * Collection contents remain unchanged.
	 *
	 * @param {Object|string} [opts] See #createSpinner
	 * @return {jQuery}
	 */
	$.fn.injectSpinner = function ( opts ) {
		return this.after( $.createSpinner( opts ) );
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.spinner
	 */

}( jQuery ) );
