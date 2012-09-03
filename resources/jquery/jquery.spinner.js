/**
 * jQuery spinner
 *
 * Simple jQuery plugin to create, inject and remove spinners.
 */
( function ( $ ) {
	$.extend( {
		/**
		 * Creates a spinner element.
		 *
		 * The argument is an object with options used to construct the spinner. These can be:
		 *   id: if given, spinner will be given an id of "mw-spinner-<id>"
		 *   size: 'small' (default) or 'large' for a 20-pixel or 32-pixel spinner
		 *   inline: if truthy (default), return an inline elem. with width & height equal to spinner size
		 *           if falsy, return a block elem. with width 100%, height equal to spinner size
		 *
		 * For backwards compatibility, if opts is a string, it is assumed to be the id option.
		 * Therefore the following two are equivalent:
		 *   $.createSpinner( 'magic' )
		 *   $.createSpinner({ id: 'magic' })
		 *
		 * It is a good practice to keep a reference to the created spinner to be able to remove it later.
		 * Alternatively one can use the id option and removeSpinner() (but make sure to choose an id
		 * that's unlikely to cause conflicts, e.g. with extensions, gadgets or user scripts).
		 *
		 * CSS classes used:
		 *   .mw-spinner for every spinner
		 *   .mw-spinner-small / .mw-spinner-large for small/large spinners
		 *   .mw-spinner-block / .mw-spinner-inline for block/inline spinners
		 *
		 * @example
		 *   // Place a big spinner at the top of the page, spanning its entire width, with spinner centered
		 *   var $spinner = $.createSpinner({ size: 'large', inline: false });
		 *   $( '#mw-content-text' ).prepend( $spinner );
		 * @example
		 *   // Place a small inline spinner next to the "Save" button
		 *   var $spinner = $.createSpinner({ size: 'small', inline: true }); // alternatively: $.createSpinner()
		 *   $( '#wpSave' ).after( $spinner );
		 *
		 * @param opts {Object} additional options (optional)
		 * @return {jQuery} spinner
		 */
		createSpinner: function ( opts ) {
			/**
			 * Default options for new spinners.
			 */
			this.defaults = {
				id: null,
				size: 'small',
				inline: true
			};

			if ( $.type(opts) !== 'object' ) {
				opts = { id: opts };
			}

			opts = $.extend( {}, this.defaults, opts );

			var $spinner = $( '<div>', { 'class': 'mw-spinner', 'title': '...' } );
			if ( opts.id ) {
				$spinner.attr( 'id', 'mw-spinner-' + opts.id );
			}

			$spinner.addClass( opts.size === 'large' ? 'mw-spinner-large' : 'mw-spinner-small' );
			$spinner.addClass( opts.inline ? 'mw-spinner-inline' : 'mw-spinner-block' );

			return $spinner;
		},

		/**
		 * Removes a spinner element.
		 *
		 * @param id {String} id as passed to createSpinner
		 * @return {jQuery} removed spinner
		 */
		removeSpinner: function ( id ) {
			return $( '#mw-spinner-' + id ).remove();
		}
	} );

	/**
	 * Injects a spinner after the elements in the jQuery collection.
	 *
	 * @param opts {Object} options, see createSpinner() for description
	 * @return {jQuery}
	 */
	$.fn.injectSpinner = function ( opts ) {
		return this.after( $.createSpinner( opts ) );
	};
}( jQuery ) );
