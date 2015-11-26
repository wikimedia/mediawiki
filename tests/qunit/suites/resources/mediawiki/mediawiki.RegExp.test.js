( function ( mw, $ ) {
	QUnit.module( 'mediawiki.RegExp' );

	QUnit.test( 'escape', 16, function ( assert ) {
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

		$.each( specials, function ( i, str ) {
			assert.propEqual( str.match( new RegExp( mw.RegExp.escape( str ) ) ), [ str ], 'Match ' + str );
		} );

		assert.equal( mw.RegExp.escape( normal ), normal, 'Alphanumerals are left alone' );
	} );

}( mediaWiki, jQuery ) );
