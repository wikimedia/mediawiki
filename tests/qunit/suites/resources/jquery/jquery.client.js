module( 'jquery.client.js' );

test( '-- Initial check', function(){
	expect(1);
	ok( jQuery.client, 'jQuery.client defined' );
});

test( 'profile', function(){
	expect(7);
	var p = $.client.profile();
	var unk = 'unknown';
	var unkOrType = function( val, type ) {
		return typeof val === type || val === unk;
	};

	equal( typeof p, 'object', 'profile() returns an object' );
	ok( unkOrType( p.layout, 'string' ), 'p.layout is a string (or "unknown")' );
	ok( unkOrType( p.layoutVersion, 'number' ), 'p.layoutVersion is a number (or "unknown")' );
	ok( unkOrType( p.platform, 'string' ), 'p.platform is a string (or "unknown")' );
	ok( unkOrType( p.version, 'string' ), 'p.version is a string (or "unknown")' );
	ok( unkOrType( p.versionBase, 'string' ), 'p.versionBase is a string (or "unknown")' );
	equal( typeof p.versionNumber, 'number', 'p.versionNumber is a number' );
});

test( 'test', function(){
	expect(1);

	// Example from WikiEditor
	var testMap = {
		'ltr': {
			'msie': [['>=', 7]],
			'firefox': [['>=', 2]],
			'opera': [['>=', 9.6]],
			'safari': [['>=', 3]],
			'chrome': [['>=', 3]],
			'netscape': [['>=', 9]],
			'blackberry': false,
			'ipod': false,
			'iphone': false
		},
		'rtl': {
			'msie': [['>=', 8]],
			'firefox': [['>=', 2]],
			'opera': [['>=', 9.6]],
			'safari': [['>=', 3]],
			'chrome': [['>=', 3]],
			'netscape': [['>=', 9]],
			'blackberry': false,
			'ipod': false,
			'iphone': false
		}
	};
	// .test() uses eval, make sure no exceptions are thrown
	// then do a basic return value type check
	var testMatch = $.client.test( testMap );

	equal( typeof testMatch, 'boolean', 'test() returns a boolean value' );

});
