/**
 * @class mw.Api.plugin.query
 */
( function ( mw, $ ) {

	var continueToken = {},
		hasMore = {};

	$.extend( mw.Api.prototype, {
		/**
		 * Will automatically continue any queries that allow it.
		 * Call multiple times for full effect.
		 *
		 * @param {string} continuation Key for remembering the continue key. Make sure this is consistent.
		 * @param {Object} params API parameters
		 * @param {Object|Function} ajaxOptions Passed to jQuery#ajax
		 * @return {jQuery.Promise}
		 */
		getAndContinue: function ( continuation, params, ajaxOptions ) {
			var promise,
				ct = continueToken[continuation];

			if ( ct !== undefined ) {
				$.each( ct, function ( name, value ) {
					params[name] = value;
				} );
			}

			promise = this.get( params, ajaxOptions );

			promise.done( function ( data ) {
				function addToken( name, value ) {
					hasMore[continuation] = true;
					ct[name] = value;
				}

				var qc = data['query-continue'];

				// Need to reset all the tokens, only saving ones that were
				// actually returned in the latest API response - this is
				// the fastest way AFAIK. Faster methods welcome.
				continueToken[continuation] = {};
				ct = continueToken[continuation];

				// Assume no more continue tokens are available - will get
				// set to true in addToken.
				hasMore[continuation] = false;

				if ( qc !== undefined ) {
					// As it turns out, this *is* an object, it just
					// looked like an array at first.
					$.each( qc, function ( i, token ) {
						$.each( token, addToken );
					} );
				}
			} );

			return promise;
		},

		/**
		 * Check if we've got more tokens in a group.
		 *
		 * @param {string} continuation Key for remembering the continue tokens. Make sure this is consistent. (see mw.Api#getAndContinue)
		 * @return {boolean/null} null means we haven't fetched this yet, true/false are self-explanatory
		 */
		haveMorePending: function ( continuation ) {
			return ( hasMore[continuation] === undefined ) ? null : hasMore[continuation];
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.query
	 */

}( mediaWiki, jQuery ) );
