( function () {
	QUnit.module( 'mediawiki.deflate', QUnit.newMwEnvironment() );

	var seed = 1234567890;
	function getPseudoRandom() {
		seed = seed * 16807 % 2147483646;
		return seed;
	}

	QUnit.test( 'deflate', function ( assert ) {
		var longData = new TextDecoder( 'utf-8' ).decode(
			// eslint-disable-next-line no-undef
			Uint32Array.from(
				{ length: 5 * 1024 * 1024 },
				getPseudoRandom
			)
		);

		var cases = [
			{
				data: 'foobar',
				expected: 'rawdeflate,S8vPT0osAgA='
			},
			{
				data: 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ',
				expected: 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ==',
				msg: 'Unicode'
			},
			{
				data: '😂𐅀𐅁𐅂𐅃𐅄𐅅𐅆𐅇𐅈𐅉𐅊𐅋𐅌𐅍𐅎𐅏',
				expected: 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD',
				msg: 'Non BMP unicode'
			},
			{
				data: longData,
				expectedLength: 330607,
				expectedHead: '7Nbbsi10mR',
				expectedTail: 'Inf9LJDw==',
				msg: '5MB data'
			}
		];
		cases.forEach( function ( caseItem ) {
			if ( caseItem.expected ) {
				assert.strictEqual( mw.deflate( caseItem.data ), caseItem.expected, caseItem.msg || caseItem.data );
			} else {
				var deflated = mw.deflate( caseItem.data );
				assert.strictEqual( deflated.length, caseItem.expectedLength, caseItem.msg + ': length' );
				assert.strictEqual( deflated.slice( 11, 21 ), caseItem.expectedHead, caseItem.msg + ': head' );
				assert.strictEqual( deflated.slice( -10 ), caseItem.expectedTail, caseItem.msg + ': tail' );
			}
		} );
	} );

}() );
