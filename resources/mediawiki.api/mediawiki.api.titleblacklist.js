/**
 * @class mw.Api.plugin.titleblacklist
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for `action=titleblacklist`.
		 * Note: This action is not provided by MediaWiki core, but as part of the TitleBlacklist extension.
		 *
		 * @param {mw.Title|string} title
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {Object|boolean} return.done.result False if title wasn't blacklisted, an object with 'reason', 'line'
		 *  and 'message' properties if title was blacklisted.
		 */
		isBlacklisted: function ( title, ok, err ) {
			var d = $.Deferred();
			// Backwards compatibility (< MW 1.20)
			d.done( ok );
			d.fail( err );

			this.get( {
					action: 'titleblacklist',
					tbaction: 'create',
					tbtitle: title.toString()
				} )
				.done( function ( data ) {
					var result;

					// this fails open (if nothing valid is returned by the api, allows the title)
					// also fails open when the API is not present, which will be most of the time
					// as this API module is part of the TitleBlacklist extension.
					if ( data.titleblacklist && data.titleblacklist.result && data.titleblacklist.result === 'blacklisted' ) {
						if ( data.titleblacklist.reason ) {
							result = {
								reason: data.titleblacklist.reason,
								line: data.titleblacklist.line,
								message: data.titleblacklist.message
							};
						} else {
							mw.log( 'mw.Api.titleblacklist::isBlacklisted> no reason data for blacklisted title', 'debug' );
							result = {
								reason: 'Blacklisted, but no reason supplied',
								line: 'Unknown',
								message: null
							};
						}
						d.resolve( result );
					} else {
						d.resolve( false );
					}
				} )
				.fail( d.reject );

			return d.promise();
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.titleblacklist
	 */

}( mediaWiki, jQuery ) );
