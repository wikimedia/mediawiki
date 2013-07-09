/**
 * @class mw.Api.plugin.query
 */
( function ( mw, $ ) {

	var continueToken = {};

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
				$.each( ct, function ( i, param ) {
					params[param.name] = param.value;
				} );
			}

			promise = this.get( params, ajaxOptions );

			promise.done( function ( data ) {
				function addToken( name, value ) {
					continueToken[continuation].push( {
						name: name,
						value: value
					} );
				}

				var qc = data['query-continue'];

				// Reset cached token - either we got a new one or we're at the end.
				continueToken[continuation] = [];

				if ( qc !== undefined ) {
					// As it turns out, this *is* an object, it just
					// looked like an array at first.
					$.each( qc, function ( i, continueToken ) {
						$.each( continueToken, addToken );
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
			if ( continueToken[continuation] === undefined ) {
				return null;
			} else if ( continueToken[continuation].length ) {
				return true;
			} else {
				return false;
			}
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.query
	 */

}( mediaWiki, jQuery ) );
