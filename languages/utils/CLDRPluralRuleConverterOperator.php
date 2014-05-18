<?php
/**
 * @author Niklas Laxström, Tim Starling
 *
 * @copyright Copyright © 2010-2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 *
 * @file
 * @since 1.20
 */

/**
 * Helper for CLDRPluralRuleConverter.
 * An operator object, representing a region of the input string (for error
 * messages), and the binary operator at that location.
 */
class CLDRPluralRuleConverterOperator extends CLDRPluralRuleConverterFragment {
	/** @var string The name */
	public $name;

	/**
	 * Each op type has three characters: left operand type, right operand type and result type
	 *
	 *   b = boolean
	 *   n = number
	 *   r = range
	 *
	 * A number is a kind of range.
	 *
	 * @var array
	 */
	private static $opTypes = array(
		'or' => 'bbb',
		'and' => 'bbb',
		'is' => 'nnb',
		'is-not' => 'nnb',
		'in' => 'nrb',
		'not-in' => 'nrb',
		'within' => 'nrb',
		'not-within' => 'nrb',
		'mod' => 'nnn',
		',' => 'rrr',
		'..' => 'nnr',
	);

	/**
	 * Map converting from the abbrevation to the full form.
	 *
	 * @var array
	 */
	private static $typeSpecMap = array(
		'b' => 'boolean',
		'n' => 'number',
		'r' => 'range',
	);

	/**
	 * Map for converting the new operators introduced in Rev 33 to the old forms
	 */
	private static $aliasMap = array(
		'%' => 'mod',
		'!=' => 'not-in',
		'=' => 'in'
	);

	/**
	 * Initialize a new instance of a CLDRPluralRuleConverterOperator object
	 *
	 * @param CLDRPluralRuleConverter $parser The parser
	 * @param string $name The operator name
	 * @param int $pos The length
	 * @param int $length
	 */
	function __construct( $parser, $name, $pos, $length ) {
		parent::__construct( $parser, $pos, $length );
		if ( isset( self::$aliasMap[$name] ) ) {
			$name = self::$aliasMap[$name];
		}
		$this->name = $name;
	}

	/**
	 * Compute the operation
	 *
	 * @param CLDRPluralRuleConverterExpression $left The left part of the expression
	 * @param CLDRPluralRuleConverterExpression $right The right part of the expression
	 * @return CLDRPluralRuleConverterExpression The result of the operation
	 */
	public function operate( $left, $right ) {
		$typeSpec = self::$opTypes[$this->name];

		$leftType = self::$typeSpecMap[$typeSpec[0]];
		$rightType = self::$typeSpecMap[$typeSpec[1]];
		$resultType = self::$typeSpecMap[$typeSpec[2]];

		$start = min( $this->pos, $left->pos, $right->pos );
		$end = max( $this->end, $left->end, $right->end );
		$length = $end - $start;

		$newExpr = new CLDRPluralRuleConverterExpression( $this->parser, $resultType,
			"{$left->rpn} {$right->rpn} {$this->name}",
			$start, $length );

		if ( !$left->isType( $leftType ) ) {
			$newExpr->error( "invalid type for left operand: expected $leftType, got {$left->type}" );
		}

		if ( !$right->isType( $rightType ) ) {
			$newExpr->error( "invalid type for right operand: expected $rightType, got {$right->type}" );
		}

		return $newExpr;
	}
}
