module( 'jquery.client.js' );

test( '-- Initial check', function(){
	expect(1);
	ok( jQuery.client, 'jQuery.client defined' );
});

test( 'profile', function(){
	expect(7);
	var profile = $.client.profile();

	equal( typeof profile, 'object', 'profile() returns an object' );
	equal( typeof profile.layout, 'string', 'profile.layout is a string' );
	equal( typeof profile.layoutVersion, 'number', 'profile.layoutVersion is a number' );
	equal( typeof profile.platform, 'string', 'profile.platform is a string' );
	equal( typeof profile.version, 'string', 'profile.version is a string' );
	equal( typeof profile.versionBase, 'string', 'profile.versionBase is a number' );
	equal( typeof profile.versionNumber, 'number', 'profile.versionNumber is a number' );
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
