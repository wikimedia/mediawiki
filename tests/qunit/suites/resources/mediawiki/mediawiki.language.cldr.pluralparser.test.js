module( 'mediawiki.cldr' );

test( '-- Initial check', function() {
	expect( 1 );
	ok( mw.cldr, 'mw.cldr defined' );
} );

var cldrPluralTestcases = [
	{
		rule     : 'n is 0',
		number   : 0,
		expected : true
	},
	{
		rule     : 'n is 0',
		number   : 1,
		expected : false
	},
	{
		rule     : 'n is 0',
		number   : 19,
		expected : false
	},
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
		rule     : 'n is 2',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n mod 100 is 1',
		number   : 1,
		expected : true
	},
	{
		rule     : 'n mod 100 is 1',
		number   : 201,
		expected : true
	},
	{
		rule     : 'n mod 100 is 1',
		number   : 11,
		expected : false
	},
	{
		rule     : 'n mod 100 is 2',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n mod 100 is 2',
		number   : 202,
		expected : true
	},
	{
		rule     : 'n mod 100 is 2',
		number   : 42,
		expected : false
	},
	{
		rule     : 'n mod 100 in 3..4',
		number   : 2,
		expected : false
	},
	{
		rule     : 'n mod 100 in 3..4',
		number   : 3,
		expected : true
	},
	{
		rule     : 'n mod 100 in 3..4',
		number   : 4,
		expected : true
	},
	{
		rule     : 'n mod 100 in 3..4',
		number   : 5,
		expected : false
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 2,
		expected : false
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 3,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 4,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 9,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 10,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 11,
		expected : false
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 12,
		expected : false
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 13,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 14,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 18,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 19,
		expected : true
	},
	{
		rule     : 'n in 3..10 or n in 13..19',
		number   : 20,
		expected : false
	},
	{
		rule     : 'n within 0..1',
		number   : 0,
		expected : true
	},
	{
		rule     : 'n within 0..1',
		number   : 0.5,
		expected : true
	},
	{
		rule     : 'n within 0..1',
		number   : 1,
		expected : true
	},
	{
		rule     : 'n within 0..1',
		number   : 2,
		expected : false
	},
	{
		rule     : 'n within 0..2 and n is not 2',
		number   : 0,
		expected : true
	},
	{
		rule     : 'n within 0..2 and n is not 2',
		number   : 0.5,
		expected : true
	},
	{
		rule     : 'n within 0..2 and n is not 2',
		number   : 1,
		expected : true
	},
	{
		rule     : 'n within 0..2 and n is not 2',
		number   : 1.5,
		expected : true
	},
	{
		rule     : 'n within 0..2 and n is not 2',
		number   : 2,
		expected : false
	},
	{
		rule     : 'n within 0..2 and n is not 2',
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
		testName = 'n=' + cldrPluralTestcases[testcase].number + ' '
			+ ( cldrPluralTestcases[testcase].expected ? "matches" : "doesn't match" )
			+ " the rule '" + cldrPluralTestcases[testcase].rule + "'";
		equal( result, cldrPluralTestcases[testcase].expected, testName );
	}
} );
