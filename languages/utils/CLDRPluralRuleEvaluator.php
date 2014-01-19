<?php
/**
 * Parse and evaluate a plural rule.
 *
 * UTS #35 Revision 33
 * http://www.unicode.org/reports/tr35/tr35-33/tr35-numbers.html#Language_Plural_Rules
 *
 * @author Niklas Laxstrom, Tim Starling
 *
 * @copyright Copyright © 2010-2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
 * or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 *
 * @file
 * @since 1.20
 */

class CLDRPluralRuleEvaluator {
	/**
	 * Evaluate a number against a set of plural rules. If a rule passes,
	 * return the index of plural rule.
	 *
	 * @param int The number to be evaluated against the rules
	 * @param array The associative array of plural rules in pluralform => rule format.
	 * @return int The index of the plural form which passed the evaluation
	 */
	public static function evaluate( $number, array $rules ) {
		$rules = self::compile( $rules );
		return self::evaluateCompiled( $number, $rules );
	}

	/**
	 * Convert a set of rules to a compiled form which is optimised for
	 * fast evaluation. The result will be an array of strings, and may be cached.
	 *
	 * @param $rules The rules to compile
	 * @return An array of compile rules.
	 */
	public static function compile( array $rules ) {
		// We can't use array_map() for this because it generates a warning if
		// there is an exception.
		foreach ( $rules as &$rule ) {
			$rule = CLDRPluralRuleConverter::convert( $rule );
		}
		return $rules;
	}

	/**
	 * Evaluate a compiled set of rules returned by compile(). Do not allow
	 * the user to edit the compiled form, or else PHP errors may result.
	 *
	 * @param string The number to be evaluated against the rules, in English, or it
	 *   may be a type convertible to string.
	 * @param array The associative array of plural rules in pluralform => rule format.
	 * @return int The index of the plural form which passed the evaluation
	 */
	public static function evaluateCompiled( $number, array $rules ) {
		// Calculate the values of the operand symbols
		$number = strval( $number );
		if ( !preg_match( '/^ -? ( ([0-9]+) (?: \. ([0-9]+) )? )$/x', $number,  $m ) ) {
			wfDebug( __METHOD__.': invalid number input, returning "other"' );
			return count( $rules );
		}
		if ( !isset( $m[3] ) ) {
			$operandSymbols = array(
				'n' => intval( $m[1] ),
				'i' => intval( $m[1] ),
				'v' => 0,
				'w' => 0,
				'f' => 0,
				't' => 0
			);
		} else {
			$absValStr = $m[1];
			$intStr = $m[2];
			$fracStr = $m[3];
			$operandSymbols = array(
				'n' => floatval( $absValStr ),
				'i' => intval( $intStr ),
				'v' => strlen( $fracStr ),
				'w' => strlen( rtrim( $fracStr, '0' ) ),
				'f' => intval( $fracStr ),
				't' => intval( rtrim( $fracStr, '0' ) ),
			);
		}

		// The compiled form is RPN, with tokens strictly delimited by
		// spaces, so this is a simple RPN evaluator.
		foreach ( $rules as $i => $rule ) {
			$stack = array();
			$zero = ord( '0' );
			$nine = ord( '9' );
			foreach ( StringUtils::explode( ' ', $rule ) as $token ) {
				$ord = ord( $token );
				if ( isset( $operandSymbols[$token] ) ) {
					$stack[] = $operandSymbols[$token];
				} elseif ( $ord >= $zero && $ord <= $nine ) {
					$stack[] = intval( $token );
				} else {
					$right = array_pop( $stack );
					$left = array_pop( $stack );
					$result = self::doOperation( $token, $left, $right );
					$stack[] = $result;
				}
			}
			if ( $stack[0] ) {
				return $i;
			}
		}
		// None of the provided rules match. The number belongs to category
		// 'other', which comes last.
		return count( $rules );
	}

	/**
	 * Do a single operation
	 *
	 * @param $token string The token string
	 * @param $left The left operand. If it is an object, its state may be destroyed.
	 * @param $right The right operand
	 * @throws CLDRPluralRuleError
	 * @return mixed
	 */
	private static function doOperation( $token, $left, $right ) {
		if ( in_array( $token, array( 'in', 'not-in', 'within', 'not-within' ) ) ) {
			if ( !( $right instanceof CLDRPluralRuleEvaluator_Range ) ) {
				$right = new CLDRPluralRuleEvaluator_Range( $right );
			}
		}
		switch ( $token ) {
			case 'or':
				return $left || $right;
			case 'and':
				return $left && $right;
			case 'is':
				return $left == $right;
			case 'is-not':
				return $left != $right;
			case 'in':
				return $right->isNumberIn( $left );
			case 'not-in':
				return !$right->isNumberIn( $left );
			case 'within':
				return $right->isNumberWithin( $left );
			case 'not-within':
				return !$right->isNumberWithin( $left );
			case 'mod':
				if ( is_int( $left ) ) {
					return (int)fmod( $left, $right );
				}
				return fmod( $left, $right );
			case ',':
				if ( $left instanceof CLDRPluralRuleEvaluator_Range ) {
					$range = $left;
				} else {
					$range = new CLDRPluralRuleEvaluator_Range( $left );
				}
				$range->add( $right );
				return $range;
			case '..':
				return new CLDRPluralRuleEvaluator_Range( $left, $right );
			default:
				throw new CLDRPluralRuleError( "Invalid RPN token" );
		}
	}
}

/**
 * Evaluator helper class representing a range list.
 */
class CLDRPluralRuleEvaluator_Range {
	public $parts = array();

	function __construct( $start, $end = false ) {
		if ( $end === false ) {
			$this->parts[] = $start;
		} else {
			$this->parts[] = array( $start, $end );
		}
	}

	/**
	 * Determine if the given number is inside the range. If $integerConstraint
	 * is true, the number must additionally be an integer if it is to match
	 * any interval part.
	 */
	function isNumberIn( $number, $integerConstraint = true ) {
		foreach ( $this->parts as $part ) {
			if ( is_array( $part ) ) {
				if ( ( !$integerConstraint || floor( $number ) === (float)$number )
					&& $number >= $part[0] && $number <= $part[1] )
				{
					return true;
				}
			} else {
				if ( $number == $part ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Readable alias for isNumberIn( $number, false ), and the implementation
	 * of the "within" operator.
	 */
	function isNumberWithin( $number ) {
		return $this->isNumberIn( $number, false );
	}

	/**
	 * Add another part to this range. The supplied new part may either be a
	 * range object itself, or a single number.
	 */
	function add( $other ) {
		if ( $other instanceof self ) {
			$this->parts = array_merge( $this->parts, $other->parts );
		} else {
			$this->parts[] = $other;
		}
	}

	/**
	 * For debugging
	 */
	function __toString() {
		$s = 'Range(';
		foreach ( $this->parts as $i => $part ) {
			if ( $i ) {
				$s .= ', ';
			}
			if ( is_array( $part ) ) {
				$s .= $part[0] . '..' . $part[1];
			} else {
				$s .= $part;
			}
		}
		$s .= ')';
		return $s;
	}

}

/**
 * Helper class for converting rules to reverse polish notation (RPN).
 */
class CLDRPluralRuleConverter {
	/**
	 * The input string
	 *
	 * @var string
	 */
	public $rule;

	/**
	 * The current position
	 *
	 * @var int
	 */
	public $pos;

	/**
	 * The past-the-end position
	 *
	 * @var int
	 */
	public $end;

	/**
	 * The operator stack
	 *
	 * @var array
	 */
	public $operators = array();

	/**
	 * The operand stack
	 *
	 * @var array
	 */
	public $operands = array();

	/**
	 * Precedence levels. Note that there's no need to worry about associativity
	 * for the level 4 operators, since they return boolean and don't accept
	 * boolean inputs.
	 */
	static $precedence = array(
		'or' => 2,
		'and' => 3,
		'is' => 4,
		'is-not' => 4,
		'in' => 4,
		'not-in' => 4,
		'within' => 4,
		'not-within' => 4,
		'mod' => 5,
		',' => 6,
		'..' => 7,
	);

	/**
	 * A character list defining whitespace, for use in strspn() etc.
	 */
	const WHITESPACE_CLASS = " \t\r\n";

	/**
	 * Same for digits. Note that the grammar given in UTS #35 doesn't allow
	 * negative numbers or decimal separators.
	 */
	const NUMBER_CLASS = '0123456789';

	/**
	 * A character list of symbolic operands.
	 */
	const OPERAND_SYMBOLS = 'nivwft';

	/**
	 * An anchored regular expression which matches a word at the current offset.
	 */
	const WORD_REGEX = '/[a-zA-Z@]+/A';

	/**
	 * Convert a rule to RPN. This is the only public entry point.
	 */
	public static function convert( $rule ) {
		$parser = new self( $rule );
		return $parser->doConvert();
	}

	/**
	 * Private constructor.
	 */
	protected function __construct( $rule ) {
		$this->rule = $rule;
		$this->pos = 0;
		$this->end = strlen( $rule );
	}

	/**
	 * Do the operation.
	 */
	protected function doConvert() {
		$expectOperator = true;

		// Iterate through all tokens, saving the operators and operands to a
		// stack per Dijkstra's shunting yard algorithm.
		while ( false !== ( $token = $this->nextToken() ) ) {
			// In this grammar, there are only binary operators, so every valid
			// rule string will alternate between operator and operand tokens.
			$expectOperator = !$expectOperator;

			if ( $token instanceof CLDRPluralRuleConverter_Expression ) {
				// Operand
				if ( $expectOperator ) {
					$token->error( 'unexpected operand' );
				}
				$this->operands[] = $token;
				continue;
			} else {
				// Operator
				if ( !$expectOperator ) {
					$token->error( 'unexpected operator' );
				}
				// Resolve higher precedence levels
				$lastOp = end( $this->operators );
				while ( $lastOp && self::$precedence[$token->name] <= self::$precedence[$lastOp->name] ) {
					$this->doOperation( $lastOp, $this->operands );
					array_pop( $this->operators );
					$lastOp = end( $this->operators );
				}
				$this->operators[] = $token;
			}
		}

		// Finish off the stack
		while ( $op = array_pop( $this->operators ) ) {
			$this->doOperation( $op, $this->operands );
		}

		// Make sure the result is sane. The first case is possible for an empty
		// string input, the second should be unreachable.
		if ( !count( $this->operands ) ) {
			$this->error( 'condition expected' );
		} elseif ( count( $this->operands ) > 1 ) {
			$this->error( 'missing operator or too many operands' );
		}

		$value = $this->operands[0];
		if ( $value->type !== 'boolean' ) {
			$this->error( 'the result must have a boolean type' );
		}

		return $this->operands[0]->rpn;
	}

	/**
	 * Fetch the next token from the input string. Return it as a
	 * CLDRPluralRuleConverter_Fragment object.
	 */
	protected function nextToken() {
		if ( $this->pos >= $this->end ) {
			return false;
		}

		// Whitespace
		$length = strspn( $this->rule, self::WHITESPACE_CLASS, $this->pos );
		$this->pos += $length;

		if ( $this->pos >= $this->end ) {
			return false;
		}

		// Number
		$length = strspn( $this->rule, self::NUMBER_CLASS, $this->pos );
		if ( $length !== 0 ) {
			$token = $this->newNumber( substr( $this->rule, $this->pos, $length ), $this->pos );
			$this->pos += $length;
			return $token;
		}

		// Two-character operators
		$op2 = substr( $this->rule, $this->pos, 2 );
		if ( $op2 === '..' || $op2 === '!=' ) {
			$token = $this->newOperator( $op2, $this->pos, 2 );
			$this->pos += 2;
			return $token;
		}

		// Single-character operators
		$op1 = $this->rule[$this->pos];
		if ( $op1 === ',' || $op1 === '=' || $op1 === '%' ) {
			$token = $this->newOperator( $op1, $this->pos, 1 );
			$this->pos ++;
			return $token;
		}

		// Word
		if ( !preg_match( self::WORD_REGEX, $this->rule, $m, 0, $this->pos ) ) {
			$this->error( 'unexpected character "' . $this->rule[$this->pos] . '"' );
		}
		$word1 = strtolower( $m[0] );
		$word2 = '';
		$nextTokenPos = $this->pos + strlen( $word1 );
		if ( $word1 === 'not' || $word1 === 'is' ) {
			// Look ahead one word
			$nextTokenPos += strspn( $this->rule, self::WHITESPACE_CLASS, $nextTokenPos );
			if ( $nextTokenPos < $this->end
					&& preg_match( self::WORD_REGEX, $this->rule, $m, 0, $nextTokenPos ) )
			{
				$word2 = strtolower( $m[0] );
				$nextTokenPos += strlen( $word2 );
			}
		}

		// Two-word operators like "is not" take precedence over single-word operators like "is"
		if ( $word2 !== '' ) {
			$bothWords = "{$word1}-{$word2}";
			if ( isset( self::$precedence[$bothWords] ) ) {
				$token = $this->newOperator( $bothWords, $this->pos, $nextTokenPos - $this->pos );
				$this->pos = $nextTokenPos;
				return $token;
			}
		}

		// Single-word operators
		if ( isset( self::$precedence[$word1] ) ) {
			$token = $this->newOperator( $word1, $this->pos, strlen( $word1 ) );
			$this->pos += strlen( $word1 );
			return $token;
		}

		// The single-character operand symbols
		if ( strpos( self::OPERAND_SYMBOLS, $word1 ) !== false ) {
			$token = $this->newNumber( $word1, $this->pos );
			$this->pos ++;
			return $token;
		}

		// Samples
		if ( $word1 === '@integer' || $word1 === '@decimal' ) {
			// Samples are like comments, they have no effect on rule evaluation.
			// They run from the first sample indicator to the end of the string.
			$this->pos = $this->end;
			return false;
		}

		$this->error( 'unrecognised word' );
	}

	/**
	 * For the binary operator $op, pop its operands off the stack and push
	 * a fragment with rpn and type members describing the result of that
	 * operation.
	 */
	protected function doOperation( $op ) {
		if ( count( $this->operands ) < 2 ) {
			$op->error( 'missing operand' );
		}
		$right = array_pop( $this->operands );
		$left = array_pop( $this->operands );
		$result = $op->operate( $left, $right );
		$this->operands[] = $result;
	}

	/**
	 * Create a numerical expression object
	 */
	protected function newNumber( $text, $pos ) {
		return new CLDRPluralRuleConverter_Expression( $this, 'number', $text, $pos, strlen( $text ) );
	}

	/**
	 * Create a binary operator
	 */
	protected function newOperator( $type, $pos, $length ) {
		return new CLDRPluralRuleConverter_Operator( $this, $type, $pos, $length );
	}

	/**
	 * Throw an error
	 */
	protected function error( $message ) {
		throw new CLDRPluralRuleError( $message );
	}
}

/**
 * Helper for CLDRPluralRuleConverter.
 * The base class for operators and expressions, describing a region of the input string.
 */
class CLDRPluralRuleConverter_Fragment {
	public $parser, $pos, $length, $end;

	function __construct( $parser, $pos, $length ) {
		$this->parser = $parser;
		$this->pos = $pos;
		$this->length = $length;
		$this->end = $pos + $length;
	}

	public function error( $message ) {
		$text = $this->getText();
		throw new CLDRPluralRuleError( "$message at position " . ( $this->pos + 1 ) . ": \"$text\"" );
	}

	public function getText() {
		return substr( $this->parser->rule, $this->pos, $this->length );
	}
}

/**
 * Helper for CLDRPluralRuleConverter.
 * An expression object, representing a region of the input string (for error
 * messages), the RPN notation used to evaluate it, and the result type for
 * validation.
 */
class CLDRPluralRuleConverter_Expression extends CLDRPluralRuleConverter_Fragment {
	public $type, $rpn;

	function __construct( $parser, $type, $rpn, $pos, $length ) {
		parent::__construct( $parser, $pos, $length );
		$this->type = $type;
		$this->rpn = $rpn;
	}

	public function isType( $type ) {
		if ( $type === 'range' && ( $this->type === 'range' || $this->type === 'number' ) ) {
			return true;
		}
		if ( $type === $this->type ) {
			return true;
		}
		return false;
	}
}

/**
 * Helper for CLDRPluralRuleConverter.
 * An operator object, representing a region of the input string (for error
 * messages), and the binary operator at that location.
 */
class CLDRPluralRuleConverter_Operator extends CLDRPluralRuleConverter_Fragment {
	public $name;

	/**
	 * Each op type has three characters: left operand type, right operand type and result type
	 *
	 *   b = boolean
	 *   n = number
	 *   r = range
	 *
	 * A number is a kind of range.
	 */
	static $opTypes = array(
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
	 */
	static $typeSpecMap = array(
		'b' => 'boolean',
		'n' => 'number',
		'r' => 'range',
	);

	/**
	 * Map for converting the new operators introduced in Rev 33 to the old forms
	 */
	static $aliasMap = array(
		'%' => 'mod',
		'!=' => 'not-in',
		'=' => 'in'
	);

	/**
	 * Initialize a new instance of a CLDRPluralRuleConverter_Operator object
	 *
	 * @param CLDRPluralRuleConverter $parser The parser
	 * @param string $name The operator name
	 * @param int $pos The position
	 * @param int $pos The length
	 */
	function __construct( $parser, $name, $pos, $length ) {
		parent::__construct( $parser, $pos, $length );
		if ( isset( self::$aliasMap[$name] ) ) {
			$name = self::$aliasMap[$name];
		}
		$this->name = $name;
	}

	public function operate( $left, $right ) {
		$typeSpec = self::$opTypes[$this->name];

		$leftType = self::$typeSpecMap[$typeSpec[0]];
		$rightType = self::$typeSpecMap[$typeSpec[1]];
		$resultType = self::$typeSpecMap[$typeSpec[2]];

		$start = min( $this->pos, $left->pos, $right->pos );
		$end = max( $this->end, $left->end, $right->end );
		$length = $end - $start;

		$newExpr = new CLDRPluralRuleConverter_Expression( $this->parser, $resultType,
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

/**
 * The exception class for all the classes in this file. This will be thrown
 * back to the caller if there is any validation error.
 */
class CLDRPluralRuleError extends MWException {
	function __construct( $message ) {
		parent::__construct( 'CLDR plural rule error: ' . $message );
	}
}
