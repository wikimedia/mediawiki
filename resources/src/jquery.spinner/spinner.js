/**
 * Provides a {@link jQuery} plugins that manage spinners.
 *
 * To use these jQuery plugins, load the `jquery.spinner` module with {@link mw.loader}.
 *
 * @example
 * mw.loader.using( 'jquery.spinner' ).then( () => {
 *       $( '#bodyContent' ).injectSpinner();
 * } );
 *
 * @module jquery.spinner
 */
( function () {

	/**
	 * Default options for new spinners,
	 * stored outside the function to share between calls.
	 *
	 * @type {module:jquery.spinner~SpinnerOpts}
	 */
	const defaults = {
		id: undefined,
		size: 'small',
		type: 'inline'
	};

	/**
	 * @typedef {Object} module:jquery.spinner~SpinnerOpts Options for {@link module:jquery.spinner.$.fn.injectSpinner injectSpinner}.
	 * @property {string} [id] If given, spinner will be given an id of "mw-spinner-{id}".
	 * @property {'small'|'large'} [size='small'] 'small' or 'large' for a 20-pixel or 32-pixel spinner.
	 * @property {'inline'|'block'} [type='inline'] 'inline' or 'block'. Inline creates an inline-block with
	 *   width and height equal to spinner size. Block is a block-level element with width 100%,
	 *   height equal to spinner size.
	 */

	$.extend( {
		/**
		 * Create a spinner element
		 *
		 * The argument is an object with options used to construct the spinner (see below).
		 *
		 * It is a good practice to keep a reference to the created spinner to be able to remove it later.
		 * Alternatively, one can use the 'id' option and {@link module:jquery.spinner.removeSpinner removeSpinner}
		 * (but make sure to choose an id that's unlikely to cause conflicts, e.g. with extensions, gadgets or user scripts).
		 *
		 * CSS classes used:
		 *
		 * - .mw-spinner for every spinner
		 * - .mw-spinner-small / .mw-spinner-large for size
		 * - .mw-spinner-block / .mw-spinner-inline for display types
		 *
		 * @example
		 * // Create a large spinner reserving all available horizontal space.
		 * const $spinner = $.createSpinner( { size: 'large', type: 'block' } );
		 * // Insert above page content.
		 * $( '#mw-content-text' ).prepend( $spinner );
		 *
		 * // Place a small inline spinner next to the "Save" button
		 * const $spinner = $.createSpinner( { size: 'small', type: 'inline' } );
		 * // Alternatively, just `$.createSpinner();` as these are the default options.
		 * $( '#wpSave' ).after( $spinner );
		 *
		 * // The following two are equivalent:
		 * $.createSpinner( 'magic' );
		 * $.createSpinner( { id: 'magic' } );
		 *
		 * @memberof module:jquery.spinner
		 * @static
		 * @inheritable
		 * @param {module:jquery.spinner~SpinnerOpts|string} [opts] Options. If a string is given, it will be treated as the value
		 *   of the {@link module:mediawiki.jqueryMsg~SpinnerOpts#id} option.
		 * @return {jQuery}
		 */
		createSpinner: ( opts ) => {
			if ( typeof opts === 'string' ) {
				opts = {
					id: opts
				};
			}

			opts = Object.assign( {}, defaults, opts );

			const $spinner = $( '<div>' ).addClass( 'mw-spinner' );
			if ( opts.id !== undefined ) {
				$spinner.attr( 'id', 'mw-spinner-' + opts.id );
			}

			$spinner
				.addClass( opts.size === 'large' ? 'mw-spinner-large' : 'mw-spinner-small' )
				.addClass( opts.type === 'block' ? 'mw-spinner-block' : 'mw-spinner-inline' );

			const $container = $( '<div>' ).addClass( 'mw-spinner-container' ).appendTo( $spinner );
			for ( let i = 0; i < 12; i++ ) {
				$container.append( $( '<div>' ) );
			}

			return $spinner;
		},

		/**
		 * Remove a spinner element
		 *
		 * @memberof module:jquery.spinner
		 * @inheritable
		 * @param {string} id Id of the spinner, as passed to {@link module:jquery.spinner.createSpinner createSpinner}
		 * @return {jQuery} The (now detached) spinner element
		 */
		removeSpinner: ( id ) => $( '#mw-spinner-' + id ).remove()
	} );

	/**
	 * Inject a spinner after each element in the collection.
	 *
	 * Inserts spinner as siblings (not children) of the target elements.
	 * Collection contents remain unchanged.
	 *
	 * @memberof module:jquery.spinner
	 * @param {module:jquery.spinner~SpinnerOpts|string} [opts] Options. If a string is given, it will be treated as the value
	 *   of the {@link module:jquery.spinner~SpinnerOpts SpinnerOpts id} option.
	 * @return {jQuery}
	 */
	$.fn.injectSpinner = function ( opts ) {
		return this.after( $.createSpinner( opts ) );
	};

	/**
	 * @class jQuery
	 * @mixes jQuery.plugin.spinner
	 */

}() );
