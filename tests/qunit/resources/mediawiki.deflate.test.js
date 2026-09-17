QUnit.module( 'mediawiki.deflate', () => {
	let seed = 1234567890;
	function getPseudoRandom() {
		seed = seed * 16807 % 2147483646;
		return seed;
	}

	const longData = new TextDecoder( 'utf-8' ).decode(
		Uint32Array.from(
			{ length: 5 * 1024 * 1024 },
			getPseudoRandom
		)
	);

	// Decompression tests are in DeflateTest.php. Any full compression strings asserted
	// here should be in the decompression test suite as well.
	QUnit.test.each( 'deflate', {
		foobar: {
			data: 'foobar',
			expected: {
				native: 'rawdeflate,S8vPT0osAgA=',
				nativeOld: 'rawdeflate,S8vPT0osAgA=',
				pako: 'rawdeflate,S8vPT0osAgA='
			}
		},
		Unicode: {
			data: 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ',
			expected: {
				native: 'rawdeflate,ASQA2//ihLPwnZKy4pml8J2TivCdk4PwnZK+8J2SuOKEtPCdkrnihK8=',
				nativeOld: 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ==',
				pako: 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ=='
			}
		},
		'Non BMP unicode': {
			data: '😂𐅀𐅁𐅂𐅃𐅄𐅅𐅆𐅇𐅈𐅉𐅊𐅋𐅌𐅍𐅎𐅏',
			expected: {
				native: 'rawdeflate,FcbBEUMAAADB1gmHRHDP/LR4JWTsa7t/r2RIxuT5lMwJyZKsyZa8k0+yJ9/kSM7k+gM=',
				nativeOld: 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD',
				pako: 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD'
			}
		},
		'5MB data': {
			data: longData,
			expectedMeta: {
				273743: {
					// Chrome
					length: 273743,
					head: '7NbbUlfXGQ',
					tail: 'md2MlnJwE='
				},
				517815: {
					// Firefox 151+
					length: 517815,
					head: '7N3bklVllv',
					tail: 'TA/xc7+b8='
				},
				329747: {
					// Firefox < 151
					length: 329747,
					head: '7NZds70FeR',
					tail: 'InX+jkAQ=='
				},
				pako: {
					length: 330607,
					head: '7Nbbsi10mR',
					tail: 'Inf9LJDw=='
				}
			}
		}
	}, async ( assert, data ) => {
		function equalDeflate( actual, method ) {
			if ( data.expected ) {
				const expected = ( method === 'pako' ?
					data.expected.pako :
					( actual === data.expected.nativeOld ?
						data.expected.nativeOld :
						data.expected.native
					)
				);
				assert.strictEqual( actual, expected, method );
			} else {
				const expectedMeta = ( method === 'pako' ?
					data.expectedMeta.pako :
					( data.expectedMeta[ actual.length ] || {} )
				);
				assert.strictEqual( actual.length, expectedMeta.length, method + ' length' );
				assert.strictEqual( actual.slice( 11, 21 ), expectedMeta.head, method + ' head' );
				assert.strictEqual( actual.slice( -10 ), expectedMeta.tail, method + ' tail' );
			}
		}

		const OriginalCompressionStream = CompressionStream;
		let deflated;

		// pako
		deflated = mw.deflate( data.data );
		equalDeflate( deflated, 'pako' );

		// deflate-raw
		deflated = await mw.deflateAsync( data.data );
		equalDeflate( deflated, 'deflate-raw' );

		// deflate
		// Replace the CompressionStream constructor to unsupport deflate-raw
		// eslint-disable-next-line prefer-arrow-callback
		sinon.replace( globalThis, 'CompressionStream', function ( type ) {
			if ( type === 'deflate-raw' ) {
				throw new Error( 'Testing deflate-raw being unavailable' );
			}
			// Call the original constructor for other arguments
			return new OriginalCompressionStream( type );
		} );
		deflated = await mw.deflateAsync( data.data );
		sinon.restore();
		equalDeflate( deflated, 'deflate' );
	} );
} );
