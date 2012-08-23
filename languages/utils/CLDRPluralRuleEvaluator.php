<?php
/**
 * Parse and evaluate a plural rule.
 *
 * http://unicode.org/reports/tr35/#Language_Plural_Rules
 *
 * @author Niklas Laxstrom, Tim Starling
 *
 * @copyright Copyright © 2010-2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
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
	 */
	public static function evaluateCompiled( $number, array $rules ) {
		// The compiled form is RPN, with tokens strictly delimited by
		// spaces, so this is a simple RPN evaluator.
		foreach ( $rules as $i => $rule  ) {
			$stack = array();
			$zero = ord( '0' );
			$nine = ord( '9' );
			foreach ( StringUtils::explode( ' ', $rule ) as $token ) {
				$ord = ord( $token );
				if ( $token === 'n' ) {
					$stack[] = $number;
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
		// None of the provided rules match. The number belongs to caregory
		// 'other' which comes last.
		return count( $rules );
	}

	/**
	 * Do a single operation
	 *
	 * @param $token string The token string
	 * @param $left The left operand. If it is an object, its state may be destroyed.
	 * @param $right The right operand
	 * @return mixed
	 */
	private static function doOperation( $token, $left, $right ) {
		if ( in_array( $token, array( 'in', 'not-in', 'within', 'not-within' ) ) ) {
			if ( !($right instanceof CLDRPluralRuleEvaluator_Range ) ) {
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
					return (int) fmod( $left, $right );
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
	var $parts = array();

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
	var $rule, $pos, $end;
	var $operators = array();
	var $operands = array();

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
	 * negative numbers or decimals.
	 */
	const NUMBER_CLASS = '0123456789';

	/**
	 * An anchored regular expression which matches a word at the current offset.
	 */
	const WORD_REGEX = '/[a-zA-Z]+/A';

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
				if  ( !$expectOperator ) {
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

		// Comma
		if ( $this->rule[$this->pos] === ',' ) {
			$token = $this->newOperator( ',', $this->pos, 1 );
			$this->pos ++;
			return $token;
		}

		// Dot dot
		if ( substr( $this->rule, $this->pos, 2 ) === '..' ) {
			$token = $this->newOperator( '..', $this->pos, 2 );
			$this->pos += 2;
			return $token;
		}

		// Word
		if ( !preg_match( self::WORD_REGEX, $this->rule, $m, 0, $this->pos ) ) {
			$this->error( 'unexpected character "' . $this->rule[$this->pos] . '"'  );
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

		// The special numerical keyword "n"
		if ( $word1 === 'n' ) {
			$token = $this->newNumber( 'n', $this->pos );
			$this->pos ++;
			return $token;
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
	var $parser, $pos, $length, $end;

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
	var $type, $rpn;

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
	var $name;

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

	function __construct( $parser, $name, $pos, $length ) {
		parent::__construct( $parser, $pos, $length );
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
