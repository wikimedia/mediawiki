( function () {
	QUnit.module( 'mediawiki.deflate', QUnit.newMwEnvironment() );

	QUnit.test( 'deflate', function ( assert ) {
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
			}
		];
		cases.forEach( function ( caseItem ) {
			assert.strictEqual( mw.deflate( caseItem.data ), caseItem.expected, caseItem.msg || caseItem.data );
		} );
	} );

}() );
