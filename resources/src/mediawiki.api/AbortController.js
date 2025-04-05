// Support: Firefox < 97, Chrome < 98, Safari < 15.4.
// mw.Api's use of AbortController requires the AbortSignal 'reason' property,
// which was not supported in earlier versions.
// https://caniuse.com/mdn-api_abortsignal_reason

/**
 * @classdesc
 * Subset of {@link AbortController} sufficient for the needs of {@link mw.Api}.
 * Used by {@link mw.Api#ajax}, {@link mw.Api#get}, {@link mw.Api#post} and related methods.
 *
 * It may be used as a fallback on browsers that don't support DOM AbortController.
 * However, it's not compliant with the spec, and can't be used as a polyfill for
 * AbortController with `fetch()` or anything else.
 *
 * Aborting requests this way is somewhat verbose in simple cases, see
 * {@link mw.Api~AbortablePromise} for an alternative style. However, it is **much** less verbose
 * when chaining multiple requests and making the whole chain abortable, which would otherwise
 * require carefully keeping track of the "current" promise at every step and forwarding the
 * `.abort()` calls (see T346984), and it's the only style that is fully compatible with native
 * promises (using `async`/`await`).
 *
 * @since 1.44
 * @hideconstructor
 * @class mw.Api~AbortController
 * @extends AbortController
 *
 * @example <caption>Cancelling an API request (using AbortController)</caption>
 * const api = new mw.Api();
 * const abort = new AbortController();
 * setTimeout( function() { abort.abort(); }, 500 );
 * api.get( { meta: 'userinfo' }, { signal: abort.signal } ).then( ... );
 *
 * @example <caption>Cancelling chained API requests</caption>
 * const api = new mw.Api();
 * const abort = new AbortController();
 * setTimeout( function() { abort.abort(); }, 500 );
 * const options = { signal: abort.signal };
 * api.get( { meta: 'userinfo' }, options ).then( function ( userinfo ) {
 *   const name = userinfo.query.userinfo.name;
 *   api.get( { list: 'usercontribs', ucuser: name }, options ).then( function ( usercontribs ) {
 *     console.log( usercontribs.query.usercontribs );
 *   } );
 * } ).catch( console.log );
 * // => DOMException: The operation was aborted.
 *
 * @example <caption>Cancelling chained API requests (using await)</caption>
 * const api = new mw.Api();
 * const abort = new AbortController();
 * setTimeout( function() { abort.abort(); }, 500 );
 * const options = { signal: abort.signal };
 * const userinfo = await api.get( { meta: 'userinfo' }, options );
 * // throws DOMException: The operation was aborted.
 * const name = userinfo.query.userinfo.name;
 * const usercontribs = await api.get( { list: 'usercontribs', ucuser: name }, options );
 * console.log( usercontribs.query.usercontribs );
 */
mw.Api.AbortController = function () {
	/**
	 * @member {AbortSignal} signal
	 * @memberof mw.Api~AbortController#
	 */
	this.signal = {
		aborted: false,
		reason: undefined,
		handlers: $.Callbacks(),
		addEventListener: function ( event, handler ) {
			if ( event === 'abort' ) {
				this.handlers.add( handler );
			}
		}
	};

	/**
	 * Cancel the promises using this controller's {@link mw.Api~AbortController#signal signal},
	 * rejecting them with the given `reason` and stopping related async operations.
	 *
	 * @method abort
	 * @param {Error} reason
	 * @memberof mw.Api~AbortController#
	 */
	this.abort = function ( reason ) {
		if ( reason === undefined ) {
			reason = new DOMException( 'The operation was aborted.', 'AbortError' );
		}
		this.signal.aborted = true;
		this.signal.reason = reason;
		this.signal.handlers.fire();
	};
};
