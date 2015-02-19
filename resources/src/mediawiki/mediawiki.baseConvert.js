( function ( mw ) {
	'use strict';

	/**
	 * Direct translation of wfBaseConvert from PHP. Should be used any time you
	 * need more than the 53 bits of precision offered by Integer.toString and
	 * parseInt.
	 *
	 * @since 1.25
	 * @param {string} input value to change the base of
	 * @param {number} sourceBase Current base of input between 2 and 36 inclusive
	 * @param {number} destBase Base to output between 2 and 36 inclusive
	 * @return {string} The input converted from sourceBase to destBase
	 */
	function baseConvert( input, sourceBase, destBase ) {
		var regex = new RegExp( '^[' + '0123456789abcdefghijklmnopqrstuvwxyz'.substr( 0, sourceBase ) + ']+$' ),
			baseChars = {
				'10': 'a', '11': 'b', '12': 'c', '13': 'd', '14': 'e', '15': 'f',
				'16': 'g', '17': 'h', '18': 'i', '19': 'j', '20': 'k', '21': 'l',
				'22': 'm', '23': 'n', '24': 'o', '25': 'p', '26': 'q', '27': 'r',
				'28': 's', '29': 't', '30': 'u', '31': 'v', '32': 'w', '33': 'x',
				'34': 'y', '35': 'z',

				'0':  0, '1':  1, '2':  2, '3':  3, '4':  4, '5': 5,
				'6':  6, '7':  7, '8':  8, '9':  9, 'a': 10, 'b': 11,
				'c': 12, 'd': 13, 'e': 14, 'f': 15, 'g': 16, 'h': 17,
				'i': 18, 'j': 19, 'k': 20, 'l': 21, 'm': 22, 'n': 23,
				'o': 24, 'p': 25, 'q': 26, 'r': 27, 's': 28, 't': 29,
				'u': 30, 'v': 31, 'w': 32, 'x': 33, 'y': 34, 'z': 35
			},
			inDigits = [],
			result = [],
			i, work, workDigits;

		input = String( input );
		if ( sourceBase < 2 ||
			sourceBase > 36 ||
			destBase < 2 ||
			destBase > 36 ||
			sourceBase !== parseInt( sourceBase, 10 ) ||
			destBase !== parseInt( destBase, 10 ) ||
			!regex.test( input )
		) {
			return false;
		}

		for ( i in input ) {
			inDigits.push( baseChars[input[i]] );
		}

		// Iterate over the input, modulo-ing out an output digit
		// at a time until input is gone.
		while ( inDigits.length ) {
			work = 0;
			workDigits = [];

			// Long division...
			for ( i in inDigits ) {
				work *= sourceBase;
				work += inDigits[i];

				if ( workDigits.length || work >= destBase ) {
					workDigits.push( parseInt( work / destBase, 10 ) );
				}
				work %= destBase;
			}

			// All that division leaves us with a remainder,
			// which is conveniently our next output digit
			result.push( baseChars[work] );

			// And we continue
			inDigits = workDigits;
		}

		return result.reverse().join( '' );
	};

	mw.baseConvert = baseConvert;

}( mediaWiki ) );
