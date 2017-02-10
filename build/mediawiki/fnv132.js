/**
 * FNV132 hash function
 *
 * This function implements the 32-bit version of FNV-1.
 * It is equivalent to hash( 'fnv132', ... ) in PHP, except
 * its output is base 36 rather than hex.
 * See <https://en.wikipedia.org/wiki/FNV_hash_function>
 *
 * @private
 * @param {string} str String to hash
 * @return {string} hash as an seven-character base 36 string
 */
function fnv132( str ) {
	/* eslint-disable no-bitwise */
	var hash = 0x811C9DC5,
		i;

	for ( i = 0; i < str.length; i++ ) {
		hash += ( hash << 1 ) + ( hash << 4 ) + ( hash << 7 ) + ( hash << 8 ) + ( hash << 24 );
		hash ^= str.charCodeAt( i );
	}

	hash = ( hash >>> 0 ).toString( 36 );
	while ( hash.length < 7 ) {
		hash = '0' + hash;
	}

	return hash;
	/* eslint-enable no-bitwise */
}

module.exports = fnv132;
