/**
 * @class mw.Api.plugin.rollback
 * @since 1.26
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for `action=rollback`.
		 *
		 * @param {string|mw.Title} page
		 * @param {string} user
		 * @param {Object} [params] Additional parameters
		 * @return {jQuery.Promise}
		 */
		rollback: function ( page, user, params ) {
			var apiPromise;
			apiPromise = this.postWithToken( 'rollback', $.extend( {
				action: 'rollback',
				title: String( page ),
				user: user
			}, params ) )
			.then( function ( data ) {
				return data.rollback;
			} );
			return apiPromise.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.rollback
	 */

}( mediaWiki, jQuery ) );
