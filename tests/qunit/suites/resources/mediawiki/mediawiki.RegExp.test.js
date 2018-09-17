( function () {
	QUnit.module( 'mediawiki.RegExp' );

	QUnit.test( 'escape', function ( assert ) {
		var specials, normal;

		specials = [
			'\\',
			'{',
			'}',
			'(',
			')',
			'[',
			']',
			'|',
			'.',
			'?',
			'*',
			'+',
			'-',
			'^',
			'$'
		];

		normal = [
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'abcdefghijklmnopqrstuvwxyz',
			'0123456789'
		].join( '' );

		specials.forEach( function ( str ) {
			assert.propEqual( str.match( new RegExp( mw.RegExp.escape( str ) ) ), [ str ], 'Match ' + str );
		} );

		assert.strictEqual( mw.RegExp.escape( normal ), normal, 'Alphanumerals are left alone' );
	} );

}() );
