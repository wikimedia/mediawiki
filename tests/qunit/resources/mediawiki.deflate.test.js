QUnit.module( 'mediawiki.deflate', function () {
	var seed = 1234567890;
	function getPseudoRandom() {
		seed = seed * 16807 % 2147483646;
		return seed;
	}

	var longData = new TextDecoder( 'utf-8' ).decode(
		Uint32Array.from(
			{ length: 5 * 1024 * 1024 },
			getPseudoRandom
		)
	);

	QUnit.test.each( 'deflate', {
		foobar: {
			data: 'foobar',
			expected: 'rawdeflate,S8vPT0osAgA='
		},
		Unicode: {
			data: 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ',
			expected: 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ=='
		},
		'Non BMP unicode': {
			data: '😂𐅀𐅁𐅂𐅃𐅄𐅅𐅆𐅇𐅈𐅉𐅊𐅋𐅌𐅍𐅎𐅏',
			expected: 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD'
		},
		'5MB data': {
			data: longData,
			expectedLength: 330607,
			expectedHead: '7Nbbsi10mR',
			expectedTail: 'Inf9LJDw=='
		}
	}, function ( assert, data ) {
		if ( data.expected ) {
			assert.strictEqual( mw.deflate( data.data ), data.expected );
		} else {
			var deflated = mw.deflate( data.data );
			assert.strictEqual( deflated.length, data.expectedLength, 'length' );
			assert.strictEqual( deflated.slice( 11, 21 ), data.expectedHead, 'head' );
			assert.strictEqual( deflated.slice( -10 ), data.expectedTail, 'tail' );
		}
	} );
} );
