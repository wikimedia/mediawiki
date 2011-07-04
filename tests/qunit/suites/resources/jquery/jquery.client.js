module( 'jquery.client.js' );

test( '-- Initial check', function() {
	expect(1);
	ok( jQuery.client, 'jQuery.client defined' );
});

test( 'profile userAgent support', function() {
	expect(8);

	// Object keyed by userAgent. Value is an array (human-readable name, client-profile object, navigator.platform value)
	// Info based on results from http://toolserver.org/~krinkle/testswarm/job/174/
	var uas = {
		// Internet Explorer 6
		// Internet Explorer 7
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)': {
			title: 'Internet Explorer 7',
			platform: 'Win32',
			profile: {
				"name": "msie",
				"layout": "trident",
				"layoutVersion": "unknown",
				"platform": "win",
				"version": "7.0",
				"versionBase": "7",
				"versionNumber": 7
			}
		},
		// Internet Explorer 8
		// Internet Explorer 9
		// Internet Explorer 10
		// Firefox 2
		// Firefox 3.5
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.1.19) Gecko/20110420 Firefox/3.5.19': {
			title: 'Firefox 3.5',
			platform: 'MacIntel',
			profile: {
				"name": "firefox",
				"layout": "gecko",
				"layoutVersion": 20110420,
				"platform": "mac",
				"version": "3.5.19",
				"versionBase": "3",
				"versionNumber": 3.5
			}
		},
		// Firefox 3.6
		'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.17) Gecko/20110422 Ubuntu/10.10 (maverick) Firefox/3.6.17': {
			title: 'Firefox 3.6',
			platform: 'Linux i686',
			profile: {
				"name": "firefox",
				"layout": "gecko",
				"layoutVersion": 20110422,
				"platform": "linux",
				"version": "3.6.17",
				"versionBase": "3",
				"versionNumber": 3.6
			}
		},
		// Firefox 4
		'Mozilla/5.0 (Windows NT 6.0; rv:2.0.1) Gecko/20100101 Firefox/4.0.1': {
			title: 'Firefox 4',
			platform: 'Win32',
			profile: {
				"name": "firefox",
				"layout": "gecko",
				"layoutVersion": 20100101,
				"platform": "win",
				"version": "4.0.1",
				"versionBase": "4",
				"versionNumber": 4
			} 
		},
		// Firefox 5
		// Safari 3
		// Safari 4
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; nl-nl) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7': {
			title: 'Safari 4',
			platform: 'MacIntel',
			profile: {
				"name": "safari",
				"layout": "webkit",
				"layoutVersion": 531,
				"platform": "mac",
				"version": "4.0.5",
				"versionBase": "4",
				"versionNumber": 4
			} 
		},
		'Mozilla/5.0 (Windows; U; Windows NT 6.0; cs-CZ) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7': {
			title: 'Safari 4',
			platform: 'Win32',
			profile: {
				"name": "safari",
				"layout": "webkit",
				"layoutVersion": 533,
				"platform": "win",
				"version": "4.0.5",
				"versionBase": "4",
				"versionNumber": 4
			}
		},
		// Safari 5
		// Opera 10
		// Chrome 5
		// Chrome 6
		// Chrome 7
		// Chrome 8
		// Chrome 9
		// Chrome 10
		// Chrome 11
		// Chrome 12
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30': {
			title: 'Chrome 12',
			platform: 'MacIntel',
			profile: {
				"name": "chrome",
				"layout": "webkit",
				"layoutVersion": 534,
				"platform": "mac",
				"version": "12.0.742.112",
				"versionBase": "12",
				"versionNumber": 12
			}
		},
		'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.68 Safari/534.30': {
			title: 'Chrome 12',
			platform: 'Linux i686',
			profile: {
				"name": "chrome",
				"layout": "webkit",
				"layoutVersion": 534,
				"platform": "linux",
				"version": "12.0.742.68",
				"versionBase": "12",
				"versionNumber": 12
			}
		}
	};

	// Generate a client profile object and compare recursively
	var uaTest = function( rawUserAgent, data ) {
		var ret = $.client.profile( {
			userAgent: rawUserAgent,
			platform: data.platform
		} );
		deepEqual( ret, data.profile, 'Client profile support check for ' + data.title + ' (' + data.platform + '): ' + rawUserAgent );
	};

	// Loop through and run tests
	$.each( uas, uaTest );
} );

test( 'profile return validation for current user agent', function() {
	expect(7);
	var p = $.client.profile();
	var unknownOrType = function( val, type, summary ) {
		return ok( typeof val === type || val === 'unknown', summary );
	};

	equal( typeof p, 'object', 'profile returns an object' );
	unknownOrType( p.layout, 'string', 'p.layout is a string (or "unknown")' );
	unknownOrType( p.layoutVersion, 'number', 'p.layoutVersion is a number (or "unknown")' );
	unknownOrType( p.platform, 'string', 'p.platform is a string (or "unknown")' );
	unknownOrType( p.version, 'string', 'p.version is a string (or "unknown")' );
	unknownOrType( p.versionBase, 'string', 'p.versionBase is a string (or "unknown")' );
	equal( typeof p.versionNumber, 'number', 'p.versionNumber is a number' );
});

test( 'test', function() {
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

	equal( typeof testMatch, 'boolean', 'test returns a boolean value' );

});
