module( 'mw.language.plurals' );

test( '-- Initial check', function() {
	expect( 1 );
	ok( mw.language, 'mw.language defined' );
} );

var pluralTestcases = {
	/*
	 * Sample:
	 *"languagecode" : [
	 * 	[ number, [ "form1", "form2", ... ],  "expected", "description" ],
	 * ]
	 */
	"en" :[
		[ 0 ,[ "one", "other" ], "other", "English plural test- 0 is other" ],
		[ 1, [ "one", "other" ], "one", "English plural test- 0 is one" ]
	],
	"hi" :[
		[ 0, [ "one", "other" ], "one", "Hindi plural test- 0 is one" ],
		[ 1, [ "one", "other" ], "one", "Hindi plural test- 0 is one" ],
		[ 2, [ "one", "other" ], "other", "Hindi plural test- 0 is other" ]
	]
};



function pluralTest( langCode, tests ) {
	QUnit.test('-- Plural Test for ' + langCode, function( assert ) {
		QUnit.expect( tests.length );
		for ( var i = 0; i < tests.length; i++ ) {
			assert.equal(
				mw.language.convertPlural( tests[i][0], tests[i][1] ),
				tests[i][2], // Expected plural form
				tests[i][3] // Description
			);
		}
	} );
}

$.each( pluralTestcases, function( langCode, tests ) {
	if ( langCode === mw.config.get( 'wgContentLanguage' ) ) {
		pluralTest( langCode, tests );
	}
} );
