/**
 * @class mw.Api.plugin.rollback
 * @since 1.28
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
			return this.postWithToken( 'rollback', $.extend( {
				action: 'rollback',
				title: String( page ),
				user: user,
				uselang: mw.config.get( 'wgUserLanguage' )
			}, params ) )
			.then( function ( data ) {
				return data.rollback;
			} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.rollback
	 */

}( mediaWiki, jQuery ) );
