<?php
/**
 * JavaScript Minifier
 *
 * @file
 * @author Paul Copperman <paul.copperman@gmail.com>
 * @license Apache-2.0
 * @license MIT
 * @license GPL-2.0-or-later
 * @license LGPL-2.1-or-later
 */

/**
 * This class is meant to safely minify javascript code, while leaving syntactically correct
 * programs intact. Other libraries, such as JSMin require a certain coding style to work
 * correctly. OTOH, libraries like jsminplus, that do parse the code correctly are rather
 * slow, because they construct a complete parse tree before outputting the code minified.
 * So this class is meant to allow arbitrary (but syntactically correct) input, while being
 * fast enough to be used for on-the-fly minifying.
 *
 * This class was written with ECMA-262 Edition 3 in mind ("ECMAScript 3"). Parsing features
 * new to ECMAScript 5 or later might not be supported. However, Edition 5.1 better reflects
 * how actual JS engines worked and work and is simpler and more readable prose. As such,
 * the below code will refer to sections of the 5.1 specification.
 *
 * See <https://www.ecma-international.org/ecma-262/5.1/>.
 */
class JavaScriptMinifier {

	/* Parsing states.
	 * The state machine is only necessary to decide whether to parse a slash as division
	 * operator or as regexp literal.
	 * States are named after the next expected item. We only distinguish states when the
	 * distinction is relevant for our purpose.
	 */
	const STATEMENT                = 0;
	const CONDITION                = 1;
	const PROPERTY_ASSIGNMENT      = 2;
	const EXPRESSION               = 3;
	const EXPRESSION_NO_NL         = 4; // only relevant for semicolon insertion
	const EXPRESSION_OP            = 5;
	const EXPRESSION_FUNC          = 6;
	const EXPRESSION_TERNARY       = 7; // used to determine the role of a colon
	const EXPRESSION_TERNARY_OP    = 8;
	const EXPRESSION_TERNARY_FUNC  = 9;
	const PAREN_EXPRESSION         = 10; // expression which is not on the top level
	const PAREN_EXPRESSION_OP      = 11;
	const PAREN_EXPRESSION_FUNC    = 12;
	const PROPERTY_EXPRESSION      = 13; // expression which is within an object literal
	const PROPERTY_EXPRESSION_OP   = 14;
	const PROPERTY_EXPRESSION_FUNC = 15;

	/* Token types */
	const TYPE_UN_OP       = 101; // unary operators
	const TYPE_INCR_OP     = 102; // ++ and --
	const TYPE_BIN_OP      = 103; // binary operators
	const TYPE_ADD_OP      = 104; // + and - which can be either unary or binary ops
	const TYPE_HOOK        = 105; // ?
	const TYPE_COLON       = 106; // :
	const TYPE_COMMA       = 107; // ,
	const TYPE_SEMICOLON   = 108; // ;
	const TYPE_BRACE_OPEN  = 109; // {
	const TYPE_BRACE_CLOSE = 110; // }
	const TYPE_PAREN_OPEN  = 111; // ( and [
	const TYPE_PAREN_CLOSE = 112; // ) and ]
	const TYPE_RETURN      = 113; // keywords: break, continue, return, throw
	const TYPE_IF          = 114; // keywords: catch, for, with, switch, while, if
	const TYPE_DO          = 115; // keywords: case, var, finally, else, do, try
	const TYPE_FUNC        = 116; // keywords: function
	const TYPE_LITERAL     = 117; // all literals, identifiers and unrecognised tokens

	const ACTION_GOTO = 201;
	const ACTION_PUSH = 202;
	const ACTION_POP = 203;

	// Sanity limit to avoid excessive memory usage
	const STACK_LIMIT = 1000;

	/**
	 * Maximum line length
	 *
	 * This is not a strict maximum, but a guideline. Longer lines will be
	 * produced when literals (e.g. quoted strings) longer than this are
	 * encountered, or when required to guard against semicolon insertion.
	 *
	 * This is a private member (instead of constant) to allow tests to
	 * set it to 1, to verify ASI and line-breaking behaviour.
	 */
	private static $maxLineLength = 1000;

	/**
	 * Returns minified JavaScript code.
	 *
	 * @param string $s JavaScript code to minify
	 * @return string Minified code
	 */
	public static function minify( $s ) {
		// First we declare a few tables that contain our parsing rules

		// $opChars : Characters which can be combined without whitespace between them.
		$opChars = [
			// ECMAScript 5.1 § 7.7 Punctuators
			// Unlike the spec, these are individual symbols, not sequences.
			'{' => true,
			'}' => true,
			'(' => true,
			')' => true,
			'[' => true,
			']' => true,
			'.' => true,
			';' => true,
			',' => true,
			'<' => true,
			'>' => true,
			'=' => true,
			'!' => true,
			'+' => true,
			'-' => true,
			'*' => true,
			'%' => true,
			'&' => true,
			'|' => true,
			'^' => true,
			'~' => true,
			'?' => true,
			':' => true,
			'/' => true,
			// ECMAScript 5.1 § 7.8.4 String Literals
			'"' => true,
			"'" => true,
		];

		// $tokenTypes : Map keywords and operators to their corresponding token type
		$tokenTypes = [
			// ECMAScript 5.1 § 11.4 Unary Operators
			// ECMAScript 5.1 § 11.6 Additive Operators
			// UnaryExpression includes PostfixExpression, which includes 'new'.
			'new'        => self::TYPE_UN_OP,
			'delete'     => self::TYPE_UN_OP,
			'void'       => self::TYPE_UN_OP,
			'typeof'     => self::TYPE_UN_OP,
			'++'         => self::TYPE_INCR_OP,
			'--'         => self::TYPE_INCR_OP,
			'+'          => self::TYPE_ADD_OP,
			'-'          => self::TYPE_ADD_OP,
			'~'          => self::TYPE_UN_OP,
			'!'          => self::TYPE_UN_OP,
			// ECMAScript 5.1 § 11.5 Multiplicative Operators
			'*'          => self::TYPE_BIN_OP,
			'/'          => self::TYPE_BIN_OP,
			'%'          => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.7 Bitwise Shift Operators
			'<<'         => self::TYPE_BIN_OP,
			'>>'         => self::TYPE_BIN_OP,
			'>>>'        => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.8 Relational Operators
			'<'          => self::TYPE_BIN_OP,
			'>'          => self::TYPE_BIN_OP,
			'<='         => self::TYPE_BIN_OP,
			'>='         => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.9 Equality Operators
			'=='         => self::TYPE_BIN_OP,
			'!='         => self::TYPE_BIN_OP,
			'==='        => self::TYPE_BIN_OP,
			'!=='        => self::TYPE_BIN_OP,
			'instanceof' => self::TYPE_BIN_OP,
			'in'         => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.10 Binary Bitwise Operators
			'&'          => self::TYPE_BIN_OP,
			'^'          => self::TYPE_BIN_OP,
			'|'          => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.11 Binary Logical Operators
			'&&'         => self::TYPE_BIN_OP,
			'||'         => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.12 Conditional Operator
			// Also known as ternary.
			'?'          => self::TYPE_HOOK,
			':'          => self::TYPE_COLON,
			// ECMAScript 5.1 § 11.13 Assignment Operators
			'='          => self::TYPE_BIN_OP,
			'*='         => self::TYPE_BIN_OP,
			'/='         => self::TYPE_BIN_OP,
			'%='         => self::TYPE_BIN_OP,
			'+='         => self::TYPE_BIN_OP,
			'-='         => self::TYPE_BIN_OP,
			'<<='        => self::TYPE_BIN_OP,
			'>>='        => self::TYPE_BIN_OP,
			'>>>='       => self::TYPE_BIN_OP,
			'&='         => self::TYPE_BIN_OP,
			'^='         => self::TYPE_BIN_OP,
			'|='         => self::TYPE_BIN_OP,
			// ECMAScript 5.1 § 11.14 Comma Operator
			','          => self::TYPE_COMMA,

			// The keywords that disallow LineTerminator before their
			// (sometimes optional) Expression or Identifier.
			//
			//    keyword ;
			//    keyword [no LineTerminator here] Identifier ;
			//    keyword [no LineTerminator here] Expression ;
			//
			// See also ECMAScript 5.1:
			// - § 12.7 The continue Statement
			// - $ 12.8 The break Statement
			// - § 12.9 The return Statement
			// - § 12.13 The throw Statement
			'continue'   => self::TYPE_RETURN,
			'break'      => self::TYPE_RETURN,
			'return'     => self::TYPE_RETURN,
			'throw'      => self::TYPE_RETURN,

			// The keywords require a parenthesised Expression or Identifier
			// before the next Statement.
			//
			//     keyword ( Expression ) Statement
			//     keyword ( Identifier ) Statement
			//
			// See also ECMAScript 5.1:
			// - § 12.5 The if Statement
			// - § 12.6 Iteration Statements (do, while, for)
			// - § 12.10 The with Statement
			// - § 12.11 The switch Statement
			// - § 12.13 The throw Statement
			'if'         => self::TYPE_IF,
			'catch'      => self::TYPE_IF,
			'while'      => self::TYPE_IF,
			'for'        => self::TYPE_IF,
			'switch'     => self::TYPE_IF,
			'with'       => self::TYPE_IF,

			// The keywords followed by an Identifier, Statement,
			// Expression, or Block.
			//
			//     var Identifier
			//     else Statement
			//     do Statement
			//     case Expression
			//     try Block
			//     finally Block
			//
			// See also ECMAScript 5.1:
			// - § 12.2 Variable Statement
			// - § 12.5 The if Statement (else)
			// - § 12.6 Iteration Statements (do, while, for)
			// - § 12.11 The switch Statement (case)
			// - § 12.14 The try Statement
			'var'        => self::TYPE_DO,
			'else'       => self::TYPE_DO,
			'do'         => self::TYPE_DO,
			'case'       => self::TYPE_DO,
			'try'        => self::TYPE_DO,
			'finally'    => self::TYPE_DO,

			// ECMAScript 5.1 § 13 Function Definition
			'function'   => self::TYPE_FUNC,

			// Can be one of:
			// - DecimalLiteral (ECMAScript 5.1 § 7.8.3 Numeric Literals)
			// - MemberExpression (ECMAScript 5.1 § 11.2 Left-Hand-Side Expressions)
			'.'          => self::TYPE_BIN_OP,

			// Can be one of:
			// - Block (ECMAScript 5.1 § 12.1 Block)
			// - ObjectLiteral (ECMAScript 5.1 § 11.1 Primary Expressions)
			'{'          => self::TYPE_BRACE_OPEN,
			'}'          => self::TYPE_BRACE_CLOSE,

			// Can be one of:
			// - Parenthesised Identifier or Expression after a
			//   TYPE_IF or TYPE_FUNC keyword.
			// - PrimaryExpression (ECMAScript 5.1 § 11.1 Primary Expressions)
			// - CallExpression (ECMAScript 5.1 § 11.2 Left-Hand-Side Expressions)
			'('          => self::TYPE_PAREN_OPEN,
			')'          => self::TYPE_PAREN_CLOSE,

			// Can be one of:
			// - ArrayLiteral (ECMAScript 5.1 § 11.1 Primary Expressions)
			'['          => self::TYPE_PAREN_OPEN,
			']'          => self::TYPE_PAREN_CLOSE,

			// Can be one of:
			// - End of any statement
			// - EmptyStatement (ECMAScript 5.1 § 12.3 Empty Statement)
			';'          => self::TYPE_SEMICOLON,
		];

		// $model : This is the main table for our state machine. For every state/token pair
		//          the desired action is defined.
		//
		// The state pushed onto the stack by ACTION_PUSH will be returned to by ACTION_POP.
		//
		// A given state/token pair MAY NOT specify both ACTION_POP and ACTION_GOTO.
		// In the event of such mistake, ACTION_POP is used instead of ACTION_GOTO.
		$model = [
			// Statement - This is the initial state.
			self::STATEMENT => [
				self::TYPE_UN_OP => [
					self::ACTION_GOTO => self::EXPRESSION,
				],
				self::TYPE_INCR_OP => [
					self::ACTION_GOTO => self::EXPRESSION,
				],
				self::TYPE_ADD_OP => [
					self::ACTION_GOTO => self::EXPRESSION,
				],
				self::TYPE_BRACE_OPEN => [
					// Use of '{' in statement context, creates a Block.
					self::ACTION_PUSH => self::STATEMENT,
				],
				self::TYPE_BRACE_CLOSE => [
					// Ends a Block
					self::ACTION_POP => true,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_RETURN => [
					self::ACTION_GOTO => self::EXPRESSION_NO_NL,
				],
				self::TYPE_IF => [
					self::ACTION_GOTO => self::CONDITION,
				],
				self::TYPE_FUNC => [
					self::ACTION_GOTO => self::CONDITION,
				],
				self::TYPE_LITERAL => [
					self::ACTION_GOTO => self::EXPRESSION_OP,
				],
			],
			self::CONDITION => [
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::STATEMENT,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
			],
			// Property assignment - This is an object literal declaration.
			// For example: `{ key: value }`
			self::PROPERTY_ASSIGNMENT => [
				self::TYPE_COLON => [
					self::ACTION_GOTO => self::PROPERTY_EXPRESSION,
				],
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::PROPERTY_ASSIGNMENT,
					self::ACTION_GOTO => self::STATEMENT,
				],
				self::TYPE_BRACE_CLOSE => [
					self::ACTION_POP => true,
				],
			],
			self::EXPRESSION => [
				self::TYPE_SEMICOLON => [
					self::ACTION_GOTO => self::STATEMENT,
				],
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::PROPERTY_ASSIGNMENT,
				],
				self::TYPE_BRACE_CLOSE => [
					self::ACTION_POP => true,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_FUNC => [
					self::ACTION_GOTO => self::EXPRESSION_FUNC,
				],
				self::TYPE_LITERAL => [
					self::ACTION_GOTO => self::EXPRESSION_OP,
				],
			],
			self::EXPRESSION_NO_NL => [
				self::TYPE_SEMICOLON => [
					self::ACTION_GOTO => self::STATEMENT,
				],
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::PROPERTY_ASSIGNMENT,
				],
				self::TYPE_BRACE_CLOSE => [
					self::ACTION_POP => true,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_FUNC => [
					self::ACTION_GOTO => self::EXPRESSION_FUNC,
				],
				self::TYPE_LITERAL => [
					self::ACTION_GOTO => self::EXPRESSION_OP,
				],
			],
			self::EXPRESSION_OP => [
				self::TYPE_BIN_OP => [
					self::ACTION_GOTO => self::EXPRESSION,
				],
				self::TYPE_ADD_OP => [
					self::ACTION_GOTO => self::EXPRESSION,
				],
				self::TYPE_HOOK => [
					self::ACTION_PUSH => self::EXPRESSION,
					self::ACTION_GOTO => self::EXPRESSION_TERNARY,
				],
				self::TYPE_COLON => [
					self::ACTION_GOTO => self::STATEMENT,
				],
				self::TYPE_COMMA => [
					self::ACTION_GOTO => self::EXPRESSION,
				],
				self::TYPE_SEMICOLON => [
					self::ACTION_GOTO => self::STATEMENT,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_BRACE_CLOSE => [
					self::ACTION_POP => true,
				],
			],
			self::EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_OP,
					self::ACTION_GOTO => self::STATEMENT,
				],
			],
			self::EXPRESSION_TERNARY => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_TERNARY_OP,
					self::ACTION_GOTO => self::PROPERTY_ASSIGNMENT,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_TERNARY_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_FUNC => [
					self::ACTION_GOTO => self::EXPRESSION_TERNARY_FUNC,
				],
				self::TYPE_LITERAL => [
					self::ACTION_GOTO => self::EXPRESSION_TERNARY_OP,
				],
			],
			self::EXPRESSION_TERNARY_OP => [
				self::TYPE_BIN_OP => [
					self::ACTION_GOTO => self::EXPRESSION_TERNARY,
				],
				self::TYPE_ADD_OP => [
					self::ACTION_GOTO => self::EXPRESSION_TERNARY,
				],
				self::TYPE_HOOK => [
					self::ACTION_PUSH => self::EXPRESSION_TERNARY,
					self::ACTION_GOTO => self::EXPRESSION_TERNARY,
				],
				self::TYPE_COMMA => [
					self::ACTION_GOTO => self::EXPRESSION_TERNARY,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_TERNARY_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_COLON => [
					self::ACTION_POP => true,
				],
			],
			self::EXPRESSION_TERNARY_FUNC => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::EXPRESSION_TERNARY_OP,
					self::ACTION_GOTO => self::STATEMENT,
				],
			],
			self::PAREN_EXPRESSION => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::PAREN_EXPRESSION_OP,
					self::ACTION_GOTO => self::PROPERTY_ASSIGNMENT,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::PAREN_EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_PAREN_CLOSE => [
					self::ACTION_POP => true,
				],
				self::TYPE_FUNC => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION_FUNC,
				],
				self::TYPE_LITERAL => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION_OP,
				],
			],
			self::PAREN_EXPRESSION_OP => [
				self::TYPE_BIN_OP => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_ADD_OP => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_HOOK => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_COLON => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_COMMA => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_SEMICOLON => [
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::PAREN_EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_PAREN_CLOSE => [
					self::ACTION_POP => true,
				],
			],
			self::PAREN_EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::PAREN_EXPRESSION_OP,
					self::ACTION_GOTO => self::STATEMENT,
				],
			],
			// Property expression - The value of a key in an object literal.
			self::PROPERTY_EXPRESSION => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::PROPERTY_EXPRESSION_OP,
					self::ACTION_GOTO => self::PROPERTY_ASSIGNMENT,
				],
				self::TYPE_BRACE_CLOSE => [
					self::ACTION_POP => true,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::PROPERTY_EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
				self::TYPE_FUNC => [
					self::ACTION_GOTO => self::PROPERTY_EXPRESSION_FUNC,
				],
				self::TYPE_LITERAL => [
					self::ACTION_GOTO => self::PROPERTY_EXPRESSION_OP,
				],
			],
			self::PROPERTY_EXPRESSION_OP => [
				self::TYPE_BIN_OP => [
					self::ACTION_GOTO => self::PROPERTY_EXPRESSION,
				],
				self::TYPE_ADD_OP => [
					self::ACTION_GOTO => self::PROPERTY_EXPRESSION,
				],
				self::TYPE_HOOK => [
					self::ACTION_PUSH => self::PROPERTY_EXPRESSION,
					self::ACTION_GOTO => self::EXPRESSION_TERNARY,
				],
				self::TYPE_COMMA => [
					self::ACTION_GOTO => self::PROPERTY_ASSIGNMENT,
				],
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::PROPERTY_EXPRESSION_OP,
				],
				self::TYPE_BRACE_CLOSE => [
					self::ACTION_POP => true,
				],
				self::TYPE_PAREN_OPEN => [
					self::ACTION_PUSH => self::PROPERTY_EXPRESSION_OP,
					self::ACTION_GOTO => self::PAREN_EXPRESSION,
				],
			],
			self::PROPERTY_EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => [
					self::ACTION_PUSH => self::PROPERTY_EXPRESSION_OP,
					self::ACTION_GOTO => self::STATEMENT,
				],
			],
		];

		// $semicolon : Rules for when a semicolon insertion is appropriate
		$semicolon = [
			self::EXPRESSION_NO_NL => [
				self::TYPE_UN_OP => true,
				self::TYPE_INCR_OP => true,
				self::TYPE_ADD_OP => true,
				self::TYPE_BRACE_OPEN => true,
				self::TYPE_PAREN_OPEN => true,
				self::TYPE_RETURN => true,
				self::TYPE_IF => true,
				self::TYPE_DO => true,
				self::TYPE_FUNC => true,
				self::TYPE_LITERAL => true
			],
			self::EXPRESSION_OP => [
				self::TYPE_UN_OP => true,
				self::TYPE_INCR_OP => true,
				self::TYPE_BRACE_OPEN => true,
				self::TYPE_RETURN => true,
				self::TYPE_IF => true,
				self::TYPE_DO => true,
				self::TYPE_FUNC => true,
				self::TYPE_LITERAL => true
			]
		];

		// $divStates : Contains all states that can be followed by a division operator
		$divStates = [
			self::EXPRESSION_OP          => true,
			self::EXPRESSION_TERNARY_OP  => true,
			self::PAREN_EXPRESSION_OP    => true,
			self::PROPERTY_EXPRESSION_OP => true
		];

		// Here's where the minifying takes place: Loop through the input, looking for tokens
		// and output them to $out, taking actions to the above defined rules when appropriate.
		$out = '';
		$pos = 0;
		$length = strlen( $s );
		$lineLength = 0;
		$newlineFound = true;
		$state = self::STATEMENT;
		$stack = [];
		$last = ';'; // Pretend that we have seen a semicolon yet
		while ( $pos < $length ) {
			// First, skip over any whitespace and multiline comments, recording whether we
			// found any newline character
			$skip = strspn( $s, " \t\n\r\xb\xc", $pos );
			if ( !$skip ) {
				$ch = $s[$pos];
				if ( $ch === '/' && substr( $s, $pos, 2 ) === '/*' ) {
					// Multiline comment. Search for the end token or EOT.
					$end = strpos( $s, '*/', $pos + 2 );
					$skip = $end === false ? $length - $pos : $end - $pos + 2;
				}
			}
			if ( $skip ) {
				// The semicolon insertion mechanism needs to know whether there was a newline
				// between two tokens, so record it now.
				if ( !$newlineFound && strcspn( $s, "\r\n", $pos, $skip ) !== $skip ) {
					$newlineFound = true;
				}
				$pos += $skip;
				continue;
			}
			// Handle C++-style comments and html comments, which are treated as single line
			// comments by the browser, regardless of whether the end tag is on the same line.
			// Handle --> the same way, but only if it's at the beginning of the line
			if ( ( $ch === '/' && substr( $s, $pos, 2 ) === '//' )
				|| ( $ch === '<' && substr( $s, $pos, 4 ) === '<!--' )
				|| ( $ch === '-' && $newlineFound && substr( $s, $pos, 3 ) === '-->' )
			) {
				$pos += strcspn( $s, "\r\n", $pos );
				continue;
			}

			// Find out which kind of token we're handling.
			// Note: $end must point past the end of the current token
			// so that `substr($s, $pos, $end - $pos)` would be the entire token.
			// In order words, $end will be the offset of the last relevant character
			// in the stream + 1, or simply put: The offset of the first character
			// of any next token in the stream.
			$end = $pos + 1;
			// Handle string literals
			if ( $ch === "'" || $ch === '"' ) {
				// Search to the end of the string literal, skipping over backslash escapes
				$search = $ch . '\\';
				do{
					// Speculatively add 2 to the end so that if we see a backslash,
					// the next iteration will start 2 characters further (one for the
					// backslash, one for the escaped character).
					// We'll correct this outside the loop.
					$end += strcspn( $s, $search, $end ) + 2;
					// If the last character in our search for a quote or a backlash
					// matched a backslash and we haven't reached the end, keep searching..
				} while ( $end - 2 < $length && $s[$end - 2] === '\\' );
				// Correction (1): Undo speculative add, keep only one (end of string literal)
				$end--;
				if ( $end > $length ) {
					// Correction (2): Loop wrongly assumed an end quote ended the search,
					// but search ended because we've reached the end. Correct $end.
					// TODO: This is invalid and should throw.
					$end--;
				}
			// We have to distinguish between regexp literals and division operators
			// A division operator is only possible in certain states
			} elseif ( $ch === '/' && !isset( $divStates[$state] ) ) {
				// Regexp literal
				for ( ; ; ) {
					// Search until we find "/" (end of regexp), "\" (backslash escapes),
					// or "[" (start of character classes).
					do{
						// Speculatively add 2 to ensure next iteration skips
						// over backslash and escaped character.
						// We'll correct this outside the loop.
						$end += strcspn( $s, '/[\\', $end ) + 2;
						// If backslash escape, keep searching...
					} while ( $end - 2 < $length && $s[$end - 2] === '\\' );
					// Correction (1): Undo speculative add, keep only one (end of regexp)
					$end--;
					if ( $end > $length ) {
						// Correction (2): Loop wrongly assumed end slash was seen
						// String ended without end of regexp. Correct $end.
						// TODO: This is invalid and should throw.
						$end--;
						break;
					}
					if ( $s[$end - 1] === '/' ) {
						break;
					}
					// (Implicit else), we must've found the start of a char class,
					// skip until we find "]" (end of char class), or "\" (backslash escape)
					do{
						// Speculatively add 2 for backslash escape.
						// We'll substract one outside the loop.
						$end += strcspn( $s, ']\\', $end ) + 2;
						// If backslash escape, keep searching...
					} while ( $end - 2 < $length && $s[$end - 2] === '\\' );
					// Correction (1): Undo speculative add, keep only one (end of regexp)
					$end--;
					if ( $end > $length ) {
						// Correction (2): Loop wrongly assumed "]" was seen
						// String ended without ending char class or regexp. Correct $end.
						// TODO: This is invalid and should throw.
						$end--;
						break;
					}
				}
				// Search past the regexp modifiers (gi)
				while ( $end < $length && ctype_alpha( $s[$end] ) ) {
					$end++;
				}
			} elseif (
				$ch === '0'
				&& ( $pos + 1 < $length ) && ( $s[$pos + 1] === 'x' || $s[$pos + 1] === 'X' )
			) {
				// Hex numeric literal
				$end++; // x or X
				$len = strspn( $s, '0123456789ABCDEFabcdef', $end );
				if ( !$len ) {
					return self::parseError(
						$s,
						$pos,
						'Expected a hexadecimal number but found ' . substr( $s, $pos, 5 ) . '...'
					);
				}
				$end += $len;
			} elseif (
				ctype_digit( $ch )
				|| ( $ch === '.' && $pos + 1 < $length && ctype_digit( $s[$pos + 1] ) )
			) {
				$end += strspn( $s, '0123456789', $end );
				$decimal = strspn( $s, '.', $end );
				if ( $decimal ) {
					if ( $decimal > 2 ) {
						return self::parseError( $s, $end, 'The number has too many decimal points' );
					}
					$end += strspn( $s, '0123456789', $end + 1 ) + $decimal;
				}
				$exponent = strspn( $s, 'eE', $end );
				if ( $exponent ) {
					if ( $exponent > 1 ) {
						return self::parseError( $s, $end, 'Number with several E' );
					}
					$end++;

					// + sign is optional; - sign is required.
					$end += strspn( $s, '-+', $end );
					$len = strspn( $s, '0123456789', $end );
					if ( !$len ) {
						return self::parseError(
							$s,
							$pos,
							'No decimal digits after e, how many zeroes should be added?'
						);
					}
					$end += $len;
				}
			} elseif ( isset( $opChars[$ch] ) ) {
				// Punctuation character. Search for the longest matching operator.
				while (
					$end < $length
					&& isset( $tokenTypes[substr( $s, $pos, $end - $pos + 1 )] )
				) {
					$end++;
				}
			} else {
				// Identifier or reserved word. Search for the end by excluding whitespace and
				// punctuation.
				$end += strcspn( $s, " \t\n.;,=<>+-{}()[]?:*/%'\"!&|^~\xb\xc\r", $end );
			}

			// Now get the token type from our type array
			$token = substr( $s, $pos, $end - $pos ); // so $end - $pos == strlen( $token )
			$type = $tokenTypes[$token] ?? self::TYPE_LITERAL;

			if ( $newlineFound && isset( $semicolon[$state][$type] ) ) {
				// This token triggers the semicolon insertion mechanism of javascript. While we
				// could add the ; token here ourselves, keeping the newline has a few advantages.
				$out .= "\n";
				$state = self::STATEMENT;
				$lineLength = 0;
			} elseif ( $lineLength + $end - $pos > self::$maxLineLength &&
					!isset( $semicolon[$state][$type] ) && $type !== self::TYPE_INCR_OP ) {
				// This line would get too long if we added $token, so add a newline first.
				// Only do this if it won't trigger semicolon insertion and if it won't
				// put a postfix increment operator on its own line, which is illegal in js.
				$out .= "\n";
				$lineLength = 0;
			// Check, whether we have to separate the token from the last one with whitespace
			} elseif ( !isset( $opChars[$last] ) && !isset( $opChars[$ch] ) ) {
				$out .= ' ';
				$lineLength++;
			// Don't accidentally create ++, -- or // tokens
			} elseif ( $last === $ch && ( $ch === '+' || $ch === '-' || $ch === '/' ) ) {
				$out .= ' ';
				$lineLength++;
			}
			if (
				$type === self::TYPE_LITERAL
				&& ( $token === 'true' || $token === 'false' )
				&& ( $state === self::EXPRESSION || $state === self::PROPERTY_EXPRESSION )
				&& $last !== '.'
			) {
				$token = ( $token === 'true' ) ? '!0' : '!1';
			}

			$out .= $token;
			$lineLength += $end - $pos; // += strlen( $token )
			$last = $s[$end - 1];
			$pos = $end;
			$newlineFound = false;

			// Now that we have output our token, transition into the new state.
			if ( isset( $model[$state][$type][self::ACTION_PUSH] ) &&
				count( $stack ) < self::STACK_LIMIT
			) {
				$stack[] = $model[$state][$type][self::ACTION_PUSH];
			}
			if ( $stack && isset( $model[$state][$type][self::ACTION_POP] ) ) {
				$state = array_pop( $stack );
			} elseif ( isset( $model[$state][$type][self::ACTION_GOTO] ) ) {
				$state = $model[$state][$type][self::ACTION_GOTO];
			}
		}
		return $out;
	}

	static function parseError( $fullJavascript, $position, $errorMsg ) {
		// TODO: Handle the error: trigger_error, throw exception, return false...
		return false;
	}
}
