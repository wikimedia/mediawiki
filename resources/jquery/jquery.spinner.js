/**
 * Functions to replace injectSpinner which makes img tags with spinners
 */
( function( $ ) {

$.extend( {
	/**
	 * Creates a spinner element
	 *
	 * @param id String id of the spinner
	 * @return jQuery spinner
	 */
	createSpinner: function( id ) {
		return $( '<div/>' )
			.attr({
				id: 'mw-spinner-' + id,
				class: 'loading-spinner',
				title: '...',
				alt: '...'
			});
	},

	/**
	 * Removes a spinner element
	 *
	 * @param id
	 */
	removeSpinner: function( id ) {
		$( '#mw-spinner-' + id ).remove();
	}
} );

/**
 * Injects a spinner after the given objects
 *
 * @param id String id of the spinner
 */
$.fn.injectSpinner = function( id ) {
	return this.after( $.createSpinner( id ) );
};

} )( jQuery );