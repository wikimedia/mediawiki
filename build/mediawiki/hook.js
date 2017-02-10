var hasOwn = Object.prototype.hasOwnProperty,
	slice = Array.prototype.slice;

module.exports = ( function () {
	var lists = {};

	/**
	 * Create an instance of mw.hook.
	 *
	 * @method hook
	 * @member mw
	 * @param {string} name Name of hook.
	 * @return {mw.hook}
	 */
	return function ( name ) {
		var list = hasOwn.call( lists, name ) ?
			lists[ name ] :
			lists[ name ] = $.Callbacks( 'memory' );

		return {
			/**
			 * Register a hook handler
			 *
			 * @param {...Function} handler Function to bind.
			 * @chainable
			 */
			add: list.add,

			/**
			 * Unregister a hook handler
			 *
			 * @param {...Function} handler Function to unbind.
			 * @chainable
			 */
			remove: list.remove,

			// eslint-disable-next-line valid-jsdoc
			/**
			 * Run a hook.
			 *
			 * @param {...Mixed} data
			 * @chainable
			 */
			fire: function () {
				return list.fireWith.call( this, null, slice.call( arguments ) );
			}
		};
	};
}() );
