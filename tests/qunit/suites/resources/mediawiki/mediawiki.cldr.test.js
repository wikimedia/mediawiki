( function ( mw, $ ) {
	QUnit.module( 'mediawiki.cldr', QUnit.newMwEnvironment() );

	var pluralTestcases = {
		/*
		 * Sample:
		 * languagecode : [
		 *   [ number, [ 'form1', 'form2', ... ],  'expected', 'description' ]
		 * ];
		 */
		en: [
			[ 0, [ 'one', 'other' ], 'other', 'English plural test- 0 is other' ],
			[ 1, [ 'one', 'other' ], 'one', 'English plural test- 1 is one' ]
		],
		fa: [
			[ 0, [ 'one', 'other' ], 'other', 'Persian plural test- 0 is other' ],
			[ 1, [ 'one', 'other' ], 'one', 'Persian plural test- 1 is one' ],
			[ 2, [ 'one', 'other' ], 'other', 'Persian plural test- 2 is other' ]
		],
		fr: [
			[ 0, [ 'one', 'other' ], 'other', 'French plural test- 0 is other' ],
			[ 1, [ 'one', 'other' ], 'one', 'French plural test- 1 is one' ]
		],
		hi: [
			[ 0, [ 'one', 'other' ], 'one', 'Hindi plural test- 0 is one' ],
			[ 1, [ 'one', 'other' ], 'one', 'Hindi plural test- 1 is one' ],
			[ 2, [ 'one', 'other' ], 'other', 'Hindi plural test- 2 is other' ]
		],
		he: [
			[ 0, [ 'one', 'other' ], 'other', 'Hebrew plural test- 0 is other' ],
			[ 1, [ 'one', 'other' ], 'one', 'Hebrew plural test- 1 is one' ],
			[ 2, [ 'one', 'other' ], 'other', 'Hebrew plural test- 2 is other with 2 forms' ],
			[ 2, [ 'one', 'dual', 'other' ], 'dual', 'Hebrew plural test- 2 is dual with 3 forms' ]
		],
		hu: [
			[ 0, [ 'one', 'other' ], 'other', 'Hungarian plural test- 0 is other' ],
			[ 1, [ 'one', 'other' ], 'one', 'Hungarian plural test- 1 is one' ],
			[ 2, [ 'one', 'other' ], 'other', 'Hungarian plural test- 2 is other' ]
		],
		hy: [
			[ 0, [ 'one', 'other' ], 'other', 'Armenian plural test- 0 is other' ],
			[ 1, [ 'one', 'other' ], 'one', 'Armenian plural test- 1 is one' ],
			[ 2, [ 'one', 'other' ], 'other', 'Armenian plural test- 2 is other' ]
		],
		ar: [
			[ 0, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'zero', 'Arabic plural test - 0 is zero' ],
			[ 1, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'one', 'Arabic plural test - 1 is one' ],
			[ 2, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'two', 'Arabic plural test - 2 is two' ],
			[ 3, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few', 'Arabic plural test - 3 is few' ],
			[ 9, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few', 'Arabic plural test - 9 is few' ],
			[ '9', [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few', 'Arabic plural test - 9 is few' ],
			[ 110, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few', 'Arabic plural test - 110 is few' ],
			[ 11, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many', 'Arabic plural test - 11 is many' ],
			[ 15, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many', 'Arabic plural test - 15 is many' ],
			[ 99, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many', 'Arabic plural test - 99 is many' ],
			[ 9999, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many', 'Arabic plural test - 9999 is many' ],
			[ 100, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other', 'Arabic plural test - 100 is other' ],
			[ 102, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other', 'Arabic plural test - 102 is other' ],
			[ 1000, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other', 'Arabic plural test - 1000 is other' ],
			[ 1.7, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other', 'Arabic plural test - 1.7 is other' ]
		]
	};

	function pluralTest( langCode, tests ) {
		QUnit.test( 'Plural Test for ' + langCode, tests.length, function ( assert ) {
			for ( var i = 0; i < tests.length; i++ ) {
				assert.equal(
					mw.language.convertPlural( tests[i][0], tests[i][1] ),
					tests[i][2],
					tests[i][3]
				);
			}
		} );
	}

	$.each( pluralTestcases, function ( langCode, tests ) {
		if ( langCode === mw.config.get( 'wgUserLanguage' ) ) {
			pluralTest( langCode, tests );
		}
	} );
}( mediaWiki, jQuery ) );
