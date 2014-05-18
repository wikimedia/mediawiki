( function ( $ ) {

	QUnit.module( 'jquery.client', QUnit.newMwEnvironment() );

	var uacount = 0,
		// Object keyed by userAgent. Value is an array (human-readable name, client-profile object, navigator.platform value)
		// Info based on results from http://toolserver.org/~krinkle/testswarm/job/174/
		uas = {
			// Internet Explorer 6
			// Internet Explorer 7
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)': {
				title: 'Internet Explorer 7',
				platform: 'Win32',
				profile: {
					name: 'msie',
					layout: 'trident',
					layoutVersion: 'unknown',
					platform: 'win',
					version: '7.0',
					versionBase: '7',
					versionNumber: 7
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
					name: 'msie',
					layout: 'trident',
					layoutVersion: 6,
					platform: 'win',
					version: '10.0',
					versionBase: '10',
					versionNumber: 10
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Internet Explorer 11
			'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv 11.0) like Gecko': {
				title: 'Internet Explorer 11',
				platform: 'Win32',
				profile: {
					name: 'msie',
					layout: 'trident',
					layoutVersion: 7,
					platform: 'win',
					version: '11.0',
					versionBase: '11',
					versionNumber: 11
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Internet Explorer 11 - Windows 8.1 x64 Modern UI
			'Mozilla/5.0 (Windows NT 6.3; Win64; x64; Trident/7.0; rv:11.0) like Gecko': {
				title: 'Internet Explorer 11',
				platform: 'Win64',
				profile: {
					name: 'msie',
					layout: 'trident',
					layoutVersion: 7,
					platform: 'win',
					version: '11.0',
					versionBase: '11',
					versionNumber: 11
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Internet Explorer 11 - Windows 8.1 x64 desktop UI
			'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko': {
				title: 'Internet Explorer 11',
				platform: 'WOW64',
				profile: {
					name: 'msie',
					layout: 'trident',
					layoutVersion: 7,
					platform: 'win',
					version: '11.0',
					versionBase: '11',
					versionNumber: 11
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
					name: 'firefox',
					layout: 'gecko',
					layoutVersion: 20110420,
					platform: 'mac',
					version: '3.5.19',
					versionBase: '3',
					versionNumber: 3.5
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
					name: 'firefox',
					layout: 'gecko',
					layoutVersion: 20110422,
					platform: 'linux',
					version: '3.6.17',
					versionBase: '3',
					versionNumber: 3.6
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
					name: 'firefox',
					layout: 'gecko',
					layoutVersion: 20100101,
					platform: 'win',
					version: '4.0.1',
					versionBase: '4',
					versionNumber: 4
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
					name: 'firefox',
					layout: 'gecko',
					layoutVersion: 20111103,
					platform: 'linux',
					version: '10.0a1',
					versionBase: '10',
					versionNumber: 10
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Iceweasel 10.0.6
			'Mozilla/5.0 (X11; Linux i686; rv:10.0.6) Gecko/20100101 Iceweasel/10.0.6': {
				title: 'Iceweasel 10.0.6',
				platform: 'Linux',
				profile: {
					name: 'iceweasel',
					layout: 'gecko',
					layoutVersion: 20100101,
					platform: 'linux',
					version: '10.0.6',
					versionBase: '10',
					versionNumber: 10
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Iceweasel 15.0.1
			'Mozilla/5.0 (X11; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1 Iceweasel/15.0.1': {
				title: 'Iceweasel 15.0.1',
				platform: 'Linux',
				profile: {
					name: 'iceweasel',
					layout: 'gecko',
					layoutVersion: 20100101,
					platform: 'linux',
					version: '15.0.1',
					versionBase: '15',
					versionNumber: 15
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
					name: 'safari',
					layout: 'webkit',
					layoutVersion: 531,
					platform: 'mac',
					version: '4.0.5',
					versionBase: '4',
					versionNumber: 4
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
					name: 'safari',
					layout: 'webkit',
					layoutVersion: 533,
					platform: 'win',
					version: '4.0.5',
					versionBase: '4',
					versionNumber: 4
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Safari 5
			// Safari 6
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/536.29.13 (KHTML, like Gecko) Version/6.0.4 Safari/536.29.13': {
				title: 'Safari 6',
				platform: 'MacIntel',
				profile: {
					name: 'safari',
					layout: 'webkit',
					layoutVersion: 536,
					platform: 'mac',
					version: '6.0.4',
					versionBase: '6',
					versionNumber: 6
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Safari 6.0.5+ (doesn't have the comma in "KHTML, like Gecko")
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 1084) AppleWebKit/536.30.1 (KHTML like Gecko) Version/6.0.5 Safari/536.30.1': {
				title: 'Safari 6',
				platform: 'MacIntel',
				profile: {
					name: 'safari',
					layout: 'webkit',
					layoutVersion: 536,
					platform: 'mac',
					version: '6.0.5',
					versionBase: '6',
					versionNumber: 6
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Opera 10+
			'Opera/9.80 (Windows NT 5.1)': {
				title: 'Opera 10+ (exact version unspecified)',
				platform: 'Win32',
				profile: {
					name: 'opera',
					layout: 'presto',
					layoutVersion: 'unknown',
					platform: 'win',
					version: '10',
					versionBase: '10',
					versionNumber: 10
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Opera 12
			'Opera/9.80 (Windows NT 5.1) Presto/2.12.388 Version/12.11': {
				title: 'Opera 12',
				platform: 'Win32',
				profile: {
					name: 'opera',
					layout: 'presto',
					layoutVersion: 'unknown',
					platform: 'win',
					version: '12.11',
					versionBase: '12',
					versionNumber: 12.11
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Opera 15 (WebKit-based)
			'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.52 Safari/537.36 OPR/15.0.1147.130': {
				title: 'Opera 15',
				platform: 'Win32',
				profile: {
					name: 'opera',
					layout: 'webkit',
					layoutVersion: 537,
					platform: 'win',
					version: '15.0.1147.130',
					versionBase: '15',
					versionNumber: 15
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
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
					name: 'chrome',
					layout: 'webkit',
					layoutVersion: 534,
					platform: 'mac',
					version: '12.0.742.112',
					versionBase: '12',
					versionNumber: 12
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
					name: 'chrome',
					layout: 'webkit',
					layoutVersion: 534,
					platform: 'linux',
					version: '12.0.742.68',
					versionBase: '12',
					versionNumber: 12
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Android WebKit Browser 2.3
			'Mozilla/5.0 (Linux; U; Android 2.3.5; en-us; HTC Vision Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1': {
				title: 'Android WebKit Browser 2.3',
				platform: 'Linux armv7l',
				profile: {
					name: 'android',
					layout: 'webkit',
					layoutVersion: 533,
					platform: 'linux',
					version: '2.3.5',
					versionBase: '2',
					versionNumber: 2.3
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Rekonq (bug 34924)
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.34 (KHTML, like Gecko) rekonq Safari/534.34': {
				title: 'Rekonq',
				platform: 'Linux i686',
				profile: {
					name: 'rekonq',
					layout: 'webkit',
					layoutVersion: 534,
					platform: 'linux',
					version: '534.34',
					versionBase: '534',
					versionNumber: 534.34
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			// Konqueror
			'Mozilla/5.0 (X11; Linux i686) KHTML/4.9.1 (like Gecko) Konqueror/4.9': {
				title: 'Konqueror',
				platform: 'Linux i686',
				profile: {
					name: 'konqueror',
					layout: 'khtml',
					layoutVersion: 'unknown',
					platform: 'linux',
					version: '4.9.1',
					versionBase: '4',
					versionNumber: 4.9
				},
				wikiEditor: {
					// '4.9' is less than '4.11'.
					ltr: false,
					rtl: false
				},
				wikiEditorLegacy: {
					// The check is missing in legacyTestMap
					ltr: true,
					rtl: true
				}
			},
			// Amazon Silk
			'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-us; Silk/1.0.13.81_10003810) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16 Silk-Accelerated=true': {
				title: 'Silk',
				platform: 'Desktop',
				profile: {
					name: 'silk',
					layout: 'webkit',
					layoutVersion: 533,
					platform: 'unknown',
					version: '1.0.13.81_10003810',
					versionBase: '1',
					versionNumber: 1
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			},
			'Mozilla/5.0 (Linux; U; Android 4.0.3; en-us; KFTT Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Silk/2.1 Mobile Safari/535.19 Silk-Accelerated=true': {
				title: 'Silk',
				platform: 'Mobile',
				profile: {
					name: 'silk',
					layout: 'webkit',
					layoutVersion: 535,
					platform: 'unknown',
					version: '2.1',
					versionBase: '2',
					versionNumber: 2.1
				},
				wikiEditor: {
					ltr: true,
					rtl: true
				}
			}
		},
		testMap = {
			// Example from WikiEditor, modified to provide version identifiers as strings and with
			// Konqueror 4.11 check added.
			'ltr': {
				'msie': [['>=', '7.0']],
				'firefox': [['>=', '2']],
				'opera': [['>=', '9.6']],
				'safari': [['>=', '3']],
				'chrome': [['>=', '3']],
				'netscape': [['>=', '9']],
				'konqueror': [['>=', '4.11']],
				'blackberry': false,
				'ipod': false,
				'iphone': false
			},
			'rtl': {
				'msie': [['>=', '8']],
				'firefox': [['>=', '2']],
				'opera': [['>=', '9.6']],
				'safari': [['>=', '3']],
				'chrome': [['>=', '3']],
				'netscape': [['>=', '9']],
				'konqueror': [['>=', '4.11']],
				'blackberry': false,
				'ipod': false,
				'iphone': false
			}
		},
		legacyTestMap = {
			// Original example from WikiEditor.
			// This is using the old, but still supported way of providing version identifiers as numbers
			// instead of strings; with this method, 4.9 would be considered larger than 4.11.
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
		}
	;

	// Count test cases
	$.each( uas, function () {
		uacount++;
	} );

	QUnit.test( 'profile( navObject )', 7, function ( assert ) {
		var p = $.client.profile();

		function unknownOrType( val, type, summary ) {
			assert.ok( typeof val === type || val === 'unknown', summary );
		}

		assert.equal( typeof p, 'object', 'profile returns an object' );
		unknownOrType( p.layout, 'string', 'p.layout is a string (or "unknown")' );
		unknownOrType( p.layoutVersion, 'number', 'p.layoutVersion is a number (or "unknown")' );
		unknownOrType( p.platform, 'string', 'p.platform is a string (or "unknown")' );
		unknownOrType( p.version, 'string', 'p.version is a string (or "unknown")' );
		unknownOrType( p.versionBase, 'string', 'p.versionBase is a string (or "unknown")' );
		assert.equal( typeof p.versionNumber, 'number', 'p.versionNumber is a number' );
	} );

	QUnit.test( 'profile( navObject ) - samples', uacount, function ( assert ) {
		// Loop through and run tests
		$.each( uas, function ( rawUserAgent, data ) {
			// Generate a client profile object and compare recursively
			var ret = $.client.profile( {
				userAgent: rawUserAgent,
				platform: data.platform
			} );
			assert.deepEqual( ret, data.profile, 'Client profile support check for ' + data.title + ' (' + data.platform + '): ' + rawUserAgent );
		} );
	} );

	QUnit.test( 'test( testMap )', 4, function ( assert ) {
		// .test() uses eval, make sure no exceptions are thrown
		// then do a basic return value type check
		var testMatch = $.client.test( testMap ),
			ie7Profile = $.client.profile( {
				'userAgent': 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
				'platform': ''
			} );

		assert.equal( typeof testMatch, 'boolean', 'map with ltr/rtl split returns a boolean value' );

		testMatch = $.client.test( testMap.ltr );

		assert.equal( typeof testMatch, 'boolean', 'simple map (without ltr/rtl split) returns a boolean value' );

		assert.equal( $.client.test( {
			'msie': null
		}, ie7Profile ), true, 'returns true if any version of a browser are allowed (null)' );

		assert.equal( $.client.test( {
			'msie': false
		}, ie7Profile ), false, 'returns false if all versions of a browser are not allowed (false)' );
	} );

	QUnit.test( 'test( testMap, exactMatchOnly )', 2, function ( assert ) {
		var ie7Profile = $.client.profile( {
			'userAgent': 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
			'platform': ''
		} );

		assert.equal( $.client.test( {
			'firefox': [['>=', 2]]
		}, ie7Profile, false ), true, 'returns true if browser not found and exactMatchOnly not set' );

		assert.equal( $.client.test( {
			'firefox': [['>=', 2]]
		}, ie7Profile, true ), false, 'returns false if browser not found and exactMatchOnly is set' );
	} );

	QUnit.test( 'test( testMap ), test( legacyTestMap ) - WikiEditor sample', uacount * 2 * 2, function ( assert ) {
		var $body = $( 'body' ),
			bodyClasses = $body.attr( 'class' );

		// Loop through and run tests
		$.each( uas, function ( agent, data ) {
			$.each( ['ltr', 'rtl'], function ( i, dir ) {
				var profile, testMatch, legacyTestMatch;
				$body.removeClass( 'ltr rtl' ).addClass( dir );
				profile = $.client.profile( {
					userAgent: agent,
					platform: data.platform
				} );
				testMatch = $.client.test( testMap, profile );
				legacyTestMatch = $.client.test( legacyTestMap, profile );
				$body.removeClass( dir );

				assert.equal(
					testMatch,
					data.wikiEditor[dir],
					'testing comparison based on ' + dir + ', ' + agent
				);
				assert.equal(
					legacyTestMatch,
					data.wikiEditorLegacy ? data.wikiEditorLegacy[dir] : data.wikiEditor[dir],
					'testing comparison based on ' + dir + ', ' + agent + ' (legacyTestMap)'
				);
			} );
		} );

		// Restore body classes
		$body.attr( 'class', bodyClasses );
	} );
}( jQuery ) );
