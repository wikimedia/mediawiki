const { formatTimeAndDate } = require( 'mediawiki.DateFormatter' );

const util = {
	/**
	 * Get the message for the given block flag
	 *
	 * @param {string} flag
	 * @return {string}
	 */
	getBlockFlagMessage( flag ) {
		// Potential messages:
		// * block-log-flags-anononly
		// * block-log-flags-nocreate
		// * block-log-flags-noautoblock
		// * block-log-flags-noemail
		// * block-log-flags-nousertalk
		// * block-log-flags-angry-autoblock
		// * block-log-flags-hiddenname
		return mw.message( 'block-log-flags-' + flag ).text();
	},

	/**
	 * Format a timestamp
	 *
	 * @param {string} timestamp
	 * @return {string}
	 */
	formatTimestamp( timestamp ) {
		if ( !timestamp || mw.util.isInfinity( timestamp ) ) {
			return mw.msg( 'infiniteblock' );
		}
		return formatTimeAndDate( new Date( timestamp ) );
	},

	/**
	 * Get the message for a given block action
	 *
	 * @param {string} action
	 * @return {string}
	 */
	getBlockActionMessage( action ) {
		// Potential messages:
		// * log-action-filter-block-block
		// * log-action-filter-block-reblock
		// * log-action-filter-block-unblock
		return mw.message( 'log-action-filter-block-' + action ).text();
	},

	/**
	 * Gets rid of unneeded numbers in quad-dotted/octet IP strings
	 * For example, 127.111.113.151/24 -> 127.111.113.0/24
	 *
	 * Convert IPv6 to uppercase.
	 *
	 * Similar to \Wikimedia\IPUtils::sanitizeRange()
	 *
	 * @param {string} range
	 * @return {string}
	 */
	sanitizeRange( range ) {
		let [ prefix, prefixBits ] = range.split( '/', 2 );
		if ( prefixBits === undefined ) {
			// Not a range
			return mw.util.sanitizeIP( range );
		}
		prefixBits = parseInt( prefixBits, 10 );

		let sep, numWords, radix, bitsPerWord;
		if ( mw.util.isIPv4Address( range, true ) ) {
			sep = '.';
			numWords = 4;
			radix = 10;
			bitsPerWord = 8;
		} else if ( mw.util.isIPv6Address( range, true ) ) {
			sep = ':';
			numWords = 8;
			radix = 16;
			bitsPerWord = 16;
			// Expand IPv6 abbreviations
			prefix = mw.util.sanitizeIP( prefix );
			if ( prefix === null ) {
				// Probably unreachable since we already validated it
				return range;
			}
		} else {
			// Invalid, pass through
			return range;
		}

		const res = [];
		const words = prefix.split( sep );
		for ( let i = 0, bitPos = 0; i < numWords; i++, bitPos += bitsPerWord ) {
			if ( bitPos + bitsPerWord <= prefixBits ) {
				// Initial unchanged word
				res.push( words[ i ] );
			} else if ( bitPos >= prefixBits ) {
				// Trailing zero word
				res.push( '0' );
			} else {
				// Partially cut off word: use reciprocal bit shifts to zero out
				// the part that's not in the prefix
				const shift = bitPos + bitsPerWord - prefixBits;
				/* eslint-disable no-bitwise */
				res.push( ( parseInt( words[ i ], radix ) >> shift << shift ).toString( radix ) );
			}
		}
		return res.join( sep ).toUpperCase() + '/' + prefixBits;
	}

};

module.exports = util;
