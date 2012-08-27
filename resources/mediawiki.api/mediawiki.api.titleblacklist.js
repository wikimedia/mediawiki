/**
 * Additional mw.Api methods to assist with API calls to the API module of the TitleBlacklist extension.
 */

( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for 'action=titleblacklist'.
		 * Note: This action is not provided by MediaWiki core, but as part of the TitleBlacklist extension.
		 *
		 * @param title {mw.Title}
		 * @param success {Function} Called on successfull request. First argument is false if title wasn't blacklisted,
		 *  object with 'reason', 'line' and 'message' properties if title was blacklisted.
		 * @param err {Function} optional callback to run if api error
		 * @return {jqXHR}
		 */
		isBlacklisted: function ( title, success, err ) {
			var	params = {
					action: 'titleblacklist',
					tbaction: 'create',
					tbtitle: title.toString()
				},
				ok = function ( data ) {
					var result;

					// this fails open (if nothing valid is returned by the api, allows the title)
					// also fails open when the API is not present, which will be most of the time
					// as this API module is part of the TitleBlacklist extension.
					if ( data.titleblacklist && data.titleblacklist.result && data.titleblacklist.result === 'blacklisted') {
						if ( data.titleblacklist.reason ) {
							result = {
								reason: data.titleblacklist.reason,
								line: data.titleblacklist.line,
								message: data.titleblacklist.message
							};
						} else {
							mw.log('mw.Api.titleblacklist::isBlacklisted> no reason data for blacklisted title', 'debug');
							result = { reason: 'Blacklisted, but no reason supplied', line: 'Unknown', message: null };
						}
						success( result );
					} else {
						success ( false );
					}
				};

			return this.get( params, { ok: ok, err: err } );
		}

	} );

}( mediaWiki, jQuery ) );
