module( 'jquery.client', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(1);
	ok( jQuery.client, 'jQuery.client defined' );
});

/** Number of user-agent defined */
var uacount = 0;

var uas = (function() {

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
			},
			wikiEditor: {
				ltr: true,
				rtl: false
			}
		},
		// Internet Explorer 8
		// Internet Explorer 9
		// Internet Explorer 10
		'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)': {
			title: 'Internet Explorer 10',
			platform: 'Win32',
			profile: {
				"name": "msie",
				"layout": "trident",
				"layoutVersion": "unknown", // should be able to report 6?
				"platform": "win",
				"version": "10.0",
				"versionBase": "10",
				"versionNumber": 10
			},
			wikiEditor: {
				ltr: true,
				rtl: true
			}
		},
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
			}
		},
		// Firefox 10 nightly build
		'Mozilla/5.0 (X11; Linux x86_64; rv:10.0a1) Gecko/20111103 Firefox/10.0a1': {
			title: 'Firefox 10 nightly',
			platform: 'Linux',
			profile: {
				"name": "firefox",
				"layout": "gecko",
				"layoutVersion": 20111103,
				"platform": "linux",
				"version": "10.0a1",
				"versionBase": "10",
				"versionNumber": 10
			},
			wikiEditor: {
				ltr: true,
				rtl: true
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
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
			},
			wikiEditor: {
				ltr: true,
				rtl: true
			}
		}
	};
	$.each( uas, function() { uacount++ });
	return uas;
})();

test( 'profile userAgent support', function() {
	expect(uacount);

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

// Example from WikiEditor
// Make sure to use raw numbers, a string like "7.0" would fail on a
// version 10 browser since in string comparaison "10" is before "7.0" :)
var testMap = {
	'ltr': {
		'msie': [['>=', 7.0]],
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

test( 'test', function() {
	expect(1);

	// .test() uses eval, make sure no exceptions are thrown
	// then do a basic return value type check
	var testMatch = $.client.test( testMap );

	equal( typeof testMatch, 'boolean', 'test returns a boolean value' );

});

test( 'User-agent matches against WikiEditor\'s compatibility map', function() {
	expect( uacount * 2 ); // double since we test both LTR and RTL

	var	$body = $( 'body' ),
		bodyClasses = $body.attr( 'class' );

	// Loop through and run tests
	$.each( uas, function ( agent, data ) {
		$.each( ['ltr', 'rtl'], function ( i, dir ) {
			$body.removeClass( 'ltr rtl' ).addClass( dir );
			var profile = $.client.profile( {
				userAgent: agent,
				platform: data.platform
			} );
			var testMatch = $.client.test( testMap, profile );
			$body.removeClass( dir );

			equal( testMatch, data.wikiEditor[dir], 'testing comparison based on ' + dir + ', ' + agent );
		});
	});

	// Restore body classes
	$body.attr( 'class', bodyClasses );
});

