/**
 * @class mw.Api.plugin.user
 * @since 1.27
 */
( function () {

	$.extend( mw.Api.prototype, {

		/**
		 * Get the current user's groups and rights.
		 *
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {Object} return.done.userInfo
		 * @return {string[]} return.done.userInfo.groups User groups that the current user belongs to
		 * @return {string[]} return.done.userInfo.rights Current user's rights
		 */
		getUserInfo: function () {
			return this.get( {
				action: 'query',
				meta: 'userinfo',
				uiprop: [ 'groups', 'rights' ]
			} ).then( function ( data ) {
				if ( data.query && data.query.userinfo ) {
					return data.query.userinfo;
				}
				return $.Deferred().reject().promise();
			} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.user
	 */

}() );
