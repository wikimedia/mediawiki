<?php
/**
 * @author Niklas Laxström
 * @file
 */

class CLDRPluralRuleEvaluatorTest extends MediaWikiTestCase {
	/**
	 * @dataProvider validTestCases
	 */
	function testValidRules( $expected, $rules, $number, $comment ) {
		$result = CLDRPluralRuleEvaluator::evaluate( $number, (array) $rules );
		$this->assertEquals( $expected, $result, $comment );
	}

	/**
	 * @dataProvider invalidTestCases
	 * @expectedException CLDRPluralRuleError
	 */
	function testInvalidRules( $rules, $comment ) {
		CLDRPluralRuleEvaluator::evaluate( 1, (array) $rules );
	}

	function validTestCases() {
		$tests = array(
			# expected, number, rule, comment
			array( 0, 'n is 1', 1, 'integer number and is' ),
			array( 0, 'n is 1', "1", 'string integer number and is' ),
			array( 0, 'n is 1', 1.0, 'float number and is' ),
			array( 0, 'n is 1', "1.0", 'string float number and is' ),
			array( 1, 'n is 1', 1.1, 'float number and is' ),
			array( 1, 'n is 1', 2, 'float number and is' ),

			array( 0, 'n in 1,3,5',     3, '' ),
			array( 1, 'n not in 1,3,5', 5, '' ),

			array( 1, 'n in 1,3,5',     2, '' ),
			array( 0, 'n not in 1,3,5', 4, '' ),

			array( 0, 'n in 1..3',      2, '' ),
			array( 0, 'n in 1..3',      3, 'in is inclusive' ),
			array( 1, 'n in 1..3',      0, '' ),

			array( 1, 'n not in 1..3',      2, '' ),
			array( 1, 'n not in 1..3',      3, 'in is inclusive' ),
			array( 0, 'n not in 1..3',      0, '' ),

			array( 1, 'n is not 1 and n is not 2 and n is not 3', 1, 'and relation' ),
			array( 0, 'n is not 1 and n is not 2 and n is not 4', 3, 'and relation' ),

			array( 0, 'n is not 1 or n is 1', 1, 'or relation' ),
			array( 1, 'n is 1 or n is 2', 3, 'or relation' ),

			array( 0, 'n              is      1', 1, 'extra whitespace' ),

			array( 0, 'n mod 3 is 1', 7, 'mod' ),
			array( 0, 'n mod 3 is not 1', 4.3, 'mod with floats' ),

			array( 0, 'n within 1..3', 2, 'within with integer' ),
			array( 0, 'n within 1..3', 2.5, 'within with float' ),
			array( 0, 'n in 1..3', 2, 'in with integer' ),
			array( 1, 'n in 1..3', 2.5, 'in with float' ),

			array( 0, 'n in 3 or n is 4 and n is 5', 3, 'and binds more tightly than or' ),
			array( 1, 'n is 3 or n is 4 and n is 5', 4, 'and binds more tightly than or' ),

			array( 0, 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99', 24, 'breton rule' ),
			array( 1, 'n mod 10 in 3..4,9 and n mod 100 not in 10..19,70..79,90..99', 25, 'breton rule' ),

			array( 0, 'n within 0..2 and n is not 2', 0, 'french rule' ),
			array( 0, 'n within 0..2 and n is not 2', 1, 'french rule' ),
			array( 0, 'n within 0..2 and n is not 2', 1.2, 'french rule' ),
			array( 1, 'n within 0..2 and n is not 2', 2, 'french rule' ),

			array( 1, 'n in 3..10,13..19', 2, 'scottish rule - ranges with comma' ),
			array( 0, 'n in 3..10,13..19', 4, 'scottish rule - ranges with comma' ),
			array( 1, 'n in 3..10,13..19', 12.999, 'scottish rule - ranges with comma' ),
			array( 0, 'n in 3..10,13..19', 13, 'scottish rule - ranges with comma' ),

			array( 0, '5 mod 3 is n', 2, 'n as result of mod - no need to pass' ),
		);

		return $tests;
	}

	function invalidTestCases() {
		$tests = array(
			array( 'n mod mod 5 is 1', 'mod mod' ),
			array( 'n', 'just n' ),
			array( 'n is in 5', 'is in' ),
		);
		return $tests;
	}

}
