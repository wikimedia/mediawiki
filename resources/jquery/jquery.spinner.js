/**
 * jQuery spinner
 *
 * Simple jQuery plugin to create, inject and remove spinners.
 */
( function( $ ) {

$.extend( {
	/**
	 * Creates a spinner element.
	 *
	 * @param id {String} id of the spinner
	 * @return {jQuery} spinner
	 */
	createSpinner: function( id ) {
		return $( '<div>' ).attr( {
			id: 'mw-spinner-' + id,
			'class': 'mw-spinner',
			title: '...'
		} );
	},

	/**
	 * Removes a spinner element.
	 *
	 * @param id {String}
	 * @return {jQuery} spinner
	 */
	removeSpinner: function( id ) {
		return $( '#mw-spinner-' + id ).remove();
	}
} );

/**
 * Injects a spinner after the elements in the jQuery collection.
 *
 * @param id String id of the spinner
 * @return {jQuery}
 */
$.fn.injectSpinner = function( id ) {
	return this.after( $.createSpinner( id ) );
};

} )( jQuery );
