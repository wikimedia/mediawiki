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
				chrome: 'rawdeflate,S8vPT0osAgA=',
				firefox: 'rawdeflate,S8vPT0osAgA=',
				pako: 'rawdeflate,S8vPT0osAgA='
			}
		},
		Unicode: {
			data: 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ',
			expected: {
				chrome: 'rawdeflate,ASQA2//ihLPwnZKy4pml8J2TivCdk4PwnZK+8J2SuOKEtPCdkrnihK8=',
				firefox: 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ==',
				pako: 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ=='
			}
		},
		'Non BMP unicode': {
			data: '😂𐅀𐅁𐅂𐅃𐅄𐅅𐅆𐅇𐅈𐅉𐅊𐅋𐅌𐅍𐅎𐅏',
			expected: {
				chrome: 'rawdeflate,FcbBEUMAAADB1gmHRHDP/LR4JWTsa7t/r2RIxuT5lMwJyZKsyZa8k0+yJ9/kSM7k+gM=',
				firefox: 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD',
				pako: 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD'
			}
		},
		'5MB data': {
			data: longData,
			expectedMeta: {
				chrome: {
					length: 273743,
					head: '7NbbUlfXGQ',
					tail: 'md2MlnJwE='
				},
				firefox: {
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
	}, ( assert, data ) => {
		[ false, true ].forEach( ( useLegacy ) => {
			const done = assert.async();
			let promise;
			let platform;
			if ( useLegacy ) {
				platform = 'pako';
				promise = Promise.resolve( mw.deflate( data.data ) );
			} else {
				platform = $.client.profile().name;
				if ( platform !== 'chrome' && platform !== 'firefox' ) {
					assert.true( true, 'Unknown browser, skipping test' );
					done();
					return;
				}
				promise = mw.deflateAsync( data.data );
			}

			promise.then( ( deflated ) => {
				const msg = useLegacy ? 'pako' : 'native';
				if ( data.expected ) {
					assert.strictEqual( deflated, data.expected[ platform ], msg );
				} else {
					assert.strictEqual( deflated.length, data.expectedMeta[ platform ].length, 'length (' + msg + ')' );
					assert.strictEqual( deflated.slice( 11, 21 ), data.expectedMeta[ platform ].head, 'head (' + msg + ')' );
					assert.strictEqual( deflated.slice( -10 ), data.expectedMeta[ platform ].tail, 'tail (' + msg + ')' );
				}
				done();
			} );
		} );
	} );
} );
