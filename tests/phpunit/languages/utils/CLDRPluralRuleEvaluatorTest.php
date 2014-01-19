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
		$result = CLDRPluralRuleEvaluator::evaluate( $number, (array)$rules );
		$this->assertEquals( $expected, $result, $comment );
	}

	/**
	 * @dataProvider invalidTestCases
	 * @expectedException CLDRPluralRuleError
	 */
	function testInvalidRules( $rules, $comment ) {
		CLDRPluralRuleEvaluator::evaluate( 1, (array)$rules );
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

			array( 0, 'n in 1,3,5', 3, '' ),
			array( 1, 'n not in 1,3,5', 5, '' ),

			array( 1, 'n in 1,3,5', 2, '' ),
			array( 0, 'n not in 1,3,5', 4, '' ),

			array( 0, 'n in 1..3', 2, '' ),
			array( 0, 'n in 1..3', 3, 'in is inclusive' ),
			array( 1, 'n in 1..3', 0, '' ),

			array( 1, 'n not in 1..3', 2, '' ),
			array( 1, 'n not in 1..3', 3, 'in is inclusive' ),
			array( 0, 'n not in 1..3', 0, '' ),

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

			# Revision 33 new operand examples
			# expected, rule, number, comment
			array( 0, 'i is 1', '1.00', 'new operand i' ),
			array( 0, 'v is 2', '1.00', 'new operand v' ),
			array( 0, 'w is 0', '1.00', 'new operand w' ),
			array( 0, 'f is 0', '1.00', 'new operand f' ),
			array( 0, 't is 0', '1.00', 'new operand t' ),

			array( 0, 'i is 1', '1.30', 'new operand i' ),
			array( 0, 'v is 2', '1.30', 'new operand v' ),
			array( 0, 'w is 1', '1.30', 'new operand w' ),
			array( 0, 'f is 30', '1.30', 'new operand f' ),
			array( 0, 't is 3', '1.30', 'new operand t' ),

			array( 0, 'i is 1', '1.03', 'new operand i' ),
			array( 0, 'v is 2', '1.03', 'new operand v' ),
			array( 0, 'w is 2', '1.03', 'new operand w' ),
			array( 0, 'f is 3', '1.03', 'new operand f' ),
			array( 0, 't is 3', '1.03', 'new operand t' ),

			# Revision 33 new operator aliases
			# expected, rule, number, comment
			array( 0, 'n % 3 is 1', 7, 'new % operator' ),
			array( 0, 'n = 1,3,5', 3, 'new = operator' ),
			array( 1, 'n != 1,3,5', 5, 'new != operator' ),

			# Revision 33 samples
			# expected, rule, number, comment
			array( 0, 'n in 1,3,5@integer 3~10, 103~110, 1003, … @decimal 3.0, 4.0, 5.0, 6.0, 7.0, 8.0, 9.0, 10.0, 103.0, 1003.0, …', 3, 'samples' ),

			# Revision 33 some test cases from CLDR
			array( 0, 'i = 1 and v = 0 or i = 0 and t = 1', '0.1', 'pt one' ),
			array( 0, 'i = 1 and v = 0 or i = 0 and t = 1', '0.01', 'pt one' ),
			array( 0, 'i = 1 and v = 0 or i = 0 and t = 1', '0.10', 'pt one' ),
			array( 0, 'i = 1 and v = 0 or i = 0 and t = 1', '0.010', 'pt one' ),
			array( 0, 'i = 1 and v = 0 or i = 0 and t = 1', '0.100', 'pt one' ),
			array( 1, 'i = 1 and v = 0 or i = 0 and t = 1', '0.0', 'pt other' ),
			array( 1, 'i = 1 and v = 0 or i = 0 and t = 1', '0.2', 'pt other' ),
			array( 1, 'i = 1 and v = 0 or i = 0 and t = 1', '10.0', 'pt other' ),
			array( 1, 'i = 1 and v = 0 or i = 0 and t = 1', '100.0', 'pt other' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '2', 'bs few' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '4', 'bs few' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '22', 'bs few' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '102', 'bs few' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '0.2', 'bs few' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '0.4', 'bs few' ),
			array( 0, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '10.2', 'bs few' ),
			array( 1, 'v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14', '10.0', 'bs other' ),

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
