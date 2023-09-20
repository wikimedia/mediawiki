/**
 * @classdesc
 * A spec-compliant promise with an extra method that allows it to be cancelled, stopping any async
 * operations that will no longer be needed since we won't use their results, like HTTP requests.
 * Used by {@link mw.Api#ajax}, {@link mw.Api#get}, {@link mw.Api#post} and related methods.
 *
 * This style is inspired by `jQuery.ajax()`, and it's very easy to use in simple cases,
 * but it becomes rather inconvenient when chaining promises using `.then()` or when
 * converting them to native promises (using `async`/`await`), since that causes the extra
 * method to be no longer accessible. It's often easier to use an AbortController instead,
 * see {@link mw.Api~AbortController} for an example.
 *
 * @since 1.22
 * @hideconstructor
 * @class mw.Api~AbortablePromise
 * @extends jQuery.Promise
 *
 * @example <caption>Cancelling an API request (using the .abort() method)</caption>
 * const api = new mw.Api();
 * const promise = api.get( { meta: 'userinfo' } );
 * promise.then( console.log );
 * promise.catch( console.log );
 * // => "http", { xhr: {…}, textStatus: "abort", exception: "abort" }
 * setTimeout( function() { promise.abort(); }, 500 );
 *
 * @example <caption>INCORRECT – The .abort() method is not accessible after calling .then()</caption>
 * const api = new mw.Api();
 * const promise = api.get( { meta: 'userinfo' } ).then( console.log );
 * setTimeout( function() { promise.abort(); }, 500 );
 * // => TypeError: promise.abort is not a function
 *
 * @example <caption>INCORRECT – The .abort() method is not accessible after converting to a native promise</caption>
 * async function getPromise() {
 *   const api = new mw.Api();
 *   return api.get( { meta: 'userinfo' } );
 * }
 * const promise = getPromise();
 * promise.then( console.log );
 * setTimeout( function() { promise.abort(); }, 500 );
 * // => TypeError: promise.abort is not a function
 */

/**
 * Cancel the promise, rejecting it and stopping related async operations.
 *
 * @method abort
 * @memberof mw.Api~AbortablePromise#
 */

// No code in this file. This is just documentation.
