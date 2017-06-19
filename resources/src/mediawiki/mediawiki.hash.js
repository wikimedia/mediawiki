( function ( mw ) {

	/**
	 * Provides an API for hashing strings.
	 *
	 * @class mw.hash
	 * @singleton
	 */
	mw.hash = {

		/**
		* An implementation of [the Jenkins' one-at-a-time hash function][0]
		*
		* @param {string} string String to hash
		* @return {number} The hash as a 32-bit unsigned integer
		*
		* @author Ori Livneh <ori@wikimedia.org>
		* @see https://jsbin.com/kejewi/4/watch?js,console
		*
		* [0]: https://en.wikipedia.org/wiki/Jenkins_hash_function
		*/
		jenkins: function jenkins( string ) {
			/* eslint-disable no-bitwise */
			var hash = 0,
				i = string.length;

			while ( i-- ) {
				hash += string.charCodeAt( i );
				hash += ( hash << 10 );
				hash ^= ( hash >> 6 );
			}
			hash += ( hash << 3 );
			hash ^= ( hash >> 11 );
			hash += ( hash << 15 );

			return hash >>> 0;
			/* eslint-enable no-bitwise */
		}
	};

}( mediaWiki ) );
