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
				var qc = data['query-continue'];

				if ( qc !== undefined ) {
					continueToken[continuation] = [];
					$.each( qc, function ( name, obj ) {
						$.each( obj, function ( name, value ) {
							var pairobj = {
								name: name,
								value: value
							};

							continueToken[continuation].push( pairobj );
						} );
					} );
				}
			} );

			return promise;
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.query
	 */

}( mediaWiki, jQuery ) );
