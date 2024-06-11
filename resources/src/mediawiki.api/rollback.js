( function () {

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * Convenience method for `action=rollback`.
		 *
		 * @since 1.28
		 * @param {string|mw.Title} page
		 * @param {string} user
		 * @param {Object} [params] Additional parameters
		 * @return {jQuery.Promise}
		 */
		rollback: function ( page, user, params ) {
			return this.postWithToken( 'rollback', Object.assign( {
				action: 'rollback',
				title: String( page ),
				user: user,
				uselang: mw.config.get( 'wgUserLanguage' )
			}, params ) ).then( ( data ) => data.rollback );
		}
	} );

}() );
