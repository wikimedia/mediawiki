module( 'mediawiki.cldr' );

test( '-- Initial check', function() {
	expect( 1 );
	ok( mw.cldr, 'mw.cldr defined' );
} );

// The test cases are mostly based on actual CLDR rules
// as well as custom rules which are needed in MediaWiki,
// but aren't present in the CLDR.
var cldrPluralTestcases = [
	{
		rule     : 'n is not 0',
		number   : 0,
		expected : false
	},
	{
		rule     : 'n is not 0',
		number   : 1,
		expected : true
	},
	{
		rule     : 'n is not 0',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n is not 1',
		number   : 0,
		expected : true
	},
	{
		rule     : 'n is not 1',
		number   : 1,
		expected : false
	},
	{
		rule     : 'n is not 1',
		number   : 2,
		expected : true
	},
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
	},
	{
		rule     : 'n not in 1..4',
		number   : 0,
		expected : true
	},
	{
		rule     : 'n not in 1..4',
		number   : 1,
		expected : false
	},
	{
		rule     : 'n not in 1..4',
		number   : 2,
		expected : false
	},
	{
		rule     : 'n not in 1..4',
		number   : 3,
		expected : false
	},
	{
		rule     : 'n not in 1..4',
		number   : 4,
		expected : false
	},
	{
		rule     : 'n not in 1..4',
		number   : 5,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 0,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 1,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 3,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 8,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 9,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 10,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 11,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 12,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 13,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 18,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 19,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 20,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 21,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 22,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 28,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 29,
		expected : true
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 30,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 110,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 111,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 112,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 118,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 119,
		expected : false
	},
	{
		rule     : 'n mod 10 in 2..9 and n mod 100 not in 11..19',
		number   : 120,
		expected : false
	},
	{
		rule     : 'n in 1,2',
		number   : 0,
		expected : false
	},
	{
		rule     : 'n in 1,2',
		number   : 1,
		expected : true
	},
	{
		rule     : 'n in 1,2',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n in 1,2',
		number   : 3,
		expected : false
	},
	{
		rule     : 'n in 2,5..7',
		number   : 1,
		expected : false
	},
	{
		rule     : 'n in 2,5..7',
		number   : 2,
		expected : true
	},
	{
		rule     : 'n in 2,5..7',
		number   : 4,
		expected : false
	},
	{
		rule     : 'n in 2,5..7',
		number   : 5,
		expected : true
	},
	{
		rule     : 'n in 2,5..7',
		number   : 6,
		expected : true
	},
	{
		rule     : 'n in 2,5..7',
		number   : 7,
		expected : true
	},
	{
		rule     : 'n in 2,5..7',
		number   : 8,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 2,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 3,
		expected : true
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 4,
		expected : true
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 5,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 8,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 9,
		expected : true
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 10,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 11,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 18,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 19,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 20,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 69,
		expected : true
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 70,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 71,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 78,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 79,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 80,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 89,
		expected : true
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 90,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 98,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 99,
		expected : false
	},
	{
		rule     : 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99',
		number   : 100,
		expected : false
	}
];

test( '-- Plural parser tests', function() {
	expect( cldrPluralTestcases.length );
	for ( var testcase = 0; testcase < cldrPluralTestcases.length; testcase++ ) {
		var result = mw.cldr.pluralRuleParser(
			cldrPluralTestcases[testcase].rule,
			cldrPluralTestcases[testcase].number
		);
		var testName = 'n=' + cldrPluralTestcases[testcase].number + ' '
			+ ( cldrPluralTestcases[testcase].expected ? "matches" : "doesn't match" )
			+ " the rule '" + cldrPluralTestcases[testcase].rule + "'";
		equal( result, cldrPluralTestcases[testcase].expected, testName );
	}
} );
