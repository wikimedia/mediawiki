// library to assist with API calls on titleblacklist

( function( mw, $ ) {

	// cached token so we don't have to keep fetching new ones for every single post
	var cachedToken = null;

	$.extend( mw.Api.prototype, { 
		/**
		 * @param {mw.Title} 
		 * @param {Function} callback to pass false on Title not blacklisted, or error text when blacklisted
		 * @param {Function} optional callback to run if api error
		 * @return ajax call object
		 */
		isBlacklisted: function( title, callback, err ) {
			var params = {
				'action': 'titleblacklist',
				'tbaction': 'create',
				'tbtitle': title.toString()
			};

			var ok = function( data ) {
				// this fails open (if nothing valid is returned by the api, allows the title)
				// also fails open when the API is not present, which will be most of the time.
				if ( data.titleblacklist && data.titleblacklist.result && data.titleblacklist.result == 'blacklisted') {
					var result;
					if ( data.titleblacklist.reason ) {
						result = {
							reason: data.titleblacklist.reason,
							line: data.titleblacklist.line,
							message: data.titleblacklist.message
						};
					} else {
						mw.log("mw.Api.titleblacklist::isBlacklisted> no reason data for blacklisted title", 'debug');
						result = { reason: "Blacklisted, but no reason supplied", line: "Unknown" };
					}
					callback( result );
				} else {
					callback ( false );
				}
			};

			return this.get( params, ok, err );

		}

	} );
} )( window.mediaWiki, jQuery );
