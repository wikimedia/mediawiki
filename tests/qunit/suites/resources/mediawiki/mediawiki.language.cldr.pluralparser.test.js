module( 'mediawiki.cldr' );

test( '-- Initial check', function() {
	expect( 1 );
	ok( mw.cldr, 'mw.cldr defined' );
} );

var cldrPluralTestcases = [
	{
		rule     : 'n is 1',
		number   : 1,
		expected : true
	},
	{
		rule     : 'n is 1',
		number   : 10,
		expected : false
	},
	{
		rule     : 'n mod 100 is 2',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n mod 100 is 2',
		number   : 3,
		expected : false
	}
];

test( '-- Plural parser tests', function() {
	expect( cldrPluralTestcases.length );
	for ( testcase = 0; testcase < cldrPluralTestcases.length; testcase++ ) {
		result = mw.cldr.pluralRuleParser(
			cldrPluralTestcases[testcase].rule,
			cldrPluralTestcases[testcase].number
		);
		testName = 'number ' + cldrPluralTestcases[testcase].number + ' '
			+ ( cldrPluralTestcases[testcase].expected ? "matches" : "doesn't match" )
			+ " rule '" + cldrPluralTestcases[testcase].rule + "'";
		equal( result, cldrPluralTestcases[testcase].expected, testName );
	}
} );
