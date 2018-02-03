<?php
/**
 * JavaScript Minifier
 *
 * @file
 * @author Paul Copperman <paul.copperman@gmail.com>
 * @license Choose any of Apache, MIT, GPL, LGPL
 */

/**
 * This class is meant to safely minify javascript code, while leaving syntactically correct
 * programs intact. Other libraries, such as JSMin require a certain coding style to work
 * correctly. OTOH, libraries like jsminplus, that do parse the code correctly are rather
 * slow, because they construct a complete parse tree before outputting the code minified.
 * So this class is meant to allow arbitrary (but syntactically correct) input, while being
 * fast enough to be used for on-the-fly minifying.
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
	const TYPE_UN_OP       = 1; // unary operators
	const TYPE_INCR_OP     = 2; // ++ and --
	const TYPE_BIN_OP      = 3; // binary operators
	const TYPE_ADD_OP      = 4; // + and - which can be either unary or binary ops
	const TYPE_HOOK        = 5; // ?
	const TYPE_COLON       = 6; // :
	const TYPE_COMMA       = 7; // ,
	const TYPE_SEMICOLON   = 8; // ;
	const TYPE_BRACE_OPEN  = 9; // {
	const TYPE_BRACE_CLOSE = 10; // }
	const TYPE_PAREN_OPEN  = 11; // ( and [
	const TYPE_PAREN_CLOSE = 12; // ) and ]
	const TYPE_RETURN      = 13; // keywords: break, continue, return, throw
	const TYPE_IF          = 14; // keywords: catch, for, with, switch, while, if
	const TYPE_DO          = 15; // keywords: case, var, finally, else, do, try
	const TYPE_FUNC        = 16; // keywords: function
	const TYPE_LITERAL     = 17; // all literals, identifiers and unrecognised tokens

	// Sanity limit to avoid excessive memory usage
	const STACK_LIMIT = 1000;

	/**
	 * NOTE: This isn't a strict maximum. Longer lines will be produced when
	 *       literals (e.g. quoted strings) longer than this are encountered
	 *       or when required to guard against semicolon insertion.
	 */
	const MAX_LINE_LENGTH = 1000;

	/**
	 * Returns minified JavaScript code.
	 *
	 * @param string $s JavaScript code to minify
	 * @return String Minified code
	 */
	public static function minify( $s ) {
		// First we declare a few tables that contain our parsing rules

		// $opChars : characters, which can be combined without whitespace in between them
		$opChars = [
			'!' => true,
			'"' => true,
			'%' => true,
			'&' => true,
			"'" => true,
			'(' => true,
			')' => true,
			'*' => true,
			'+' => true,
			',' => true,
			'-' => true,
			'.' => true,
			'/' => true,
			':' => true,
			';' => true,
			'<' => true,
			'=' => true,
			'>' => true,
			'?' => true,
			'[' => true,
			']' => true,
			'^' => true,
			'{' => true,
			'|' => true,
			'}' => true,
			'~' => true
		];

		// $tokenTypes : maps keywords and operators to their corresponding token type
		$tokenTypes = [
			'!'          => self::TYPE_UN_OP,
			'~'          => self::TYPE_UN_OP,
			'delete'     => self::TYPE_UN_OP,
			'new'        => self::TYPE_UN_OP,
			'typeof'     => self::TYPE_UN_OP,
			'void'       => self::TYPE_UN_OP,
			'++'         => self::TYPE_INCR_OP,
			'--'         => self::TYPE_INCR_OP,
			'!='         => self::TYPE_BIN_OP,
			'!=='        => self::TYPE_BIN_OP,
			'%'          => self::TYPE_BIN_OP,
			'%='         => self::TYPE_BIN_OP,
			'&'          => self::TYPE_BIN_OP,
			'&&'         => self::TYPE_BIN_OP,
			'&='         => self::TYPE_BIN_OP,
			'*'          => self::TYPE_BIN_OP,
			'*='         => self::TYPE_BIN_OP,
			'+='         => self::TYPE_BIN_OP,
			'-='         => self::TYPE_BIN_OP,
			'.'          => self::TYPE_BIN_OP,
			'/'          => self::TYPE_BIN_OP,
			'/='         => self::TYPE_BIN_OP,
			'<'          => self::TYPE_BIN_OP,
			'<<'         => self::TYPE_BIN_OP,
			'<<='        => self::TYPE_BIN_OP,
			'<='         => self::TYPE_BIN_OP,
			'='          => self::TYPE_BIN_OP,
			'=='         => self::TYPE_BIN_OP,
			'==='        => self::TYPE_BIN_OP,
			'>'          => self::TYPE_BIN_OP,
			'>='         => self::TYPE_BIN_OP,
			'>>'         => self::TYPE_BIN_OP,
			'>>='        => self::TYPE_BIN_OP,
			'>>>'        => self::TYPE_BIN_OP,
			'>>>='       => self::TYPE_BIN_OP,
			'^'          => self::TYPE_BIN_OP,
			'^='         => self::TYPE_BIN_OP,
			'|'          => self::TYPE_BIN_OP,
			'|='         => self::TYPE_BIN_OP,
			'||'         => self::TYPE_BIN_OP,
			'in'         => self::TYPE_BIN_OP,
			'instanceof' => self::TYPE_BIN_OP,
			'+'          => self::TYPE_ADD_OP,
			'-'          => self::TYPE_ADD_OP,
			'?'          => self::TYPE_HOOK,
			':'          => self::TYPE_COLON,
			','          => self::TYPE_COMMA,
			';'          => self::TYPE_SEMICOLON,
			'{'          => self::TYPE_BRACE_OPEN,
			'}'          => self::TYPE_BRACE_CLOSE,
			'('          => self::TYPE_PAREN_OPEN,
			'['          => self::TYPE_PAREN_OPEN,
			')'          => self::TYPE_PAREN_CLOSE,
			']'          => self::TYPE_PAREN_CLOSE,
			'break'      => self::TYPE_RETURN,
			'continue'   => self::TYPE_RETURN,
			'return'     => self::TYPE_RETURN,
			'throw'      => self::TYPE_RETURN,
			'catch'      => self::TYPE_IF,
			'for'        => self::TYPE_IF,
			'if'         => self::TYPE_IF,
			'switch'     => self::TYPE_IF,
			'while'      => self::TYPE_IF,
			'with'       => self::TYPE_IF,
			'case'       => self::TYPE_DO,
			'do'         => self::TYPE_DO,
			'else'       => self::TYPE_DO,
			'finally'    => self::TYPE_DO,
			'try'        => self::TYPE_DO,
			'var'        => self::TYPE_DO,
			'function'   => self::TYPE_FUNC
		];

		// $goto : This is the main table for our state machine. For every state/token pair
		//         the following state is defined. When no rule exists for a given pair,
		//         the state is left unchanged.
		$goto = [
			self::STATEMENT => [
				self::TYPE_UN_OP      => self::EXPRESSION,
				self::TYPE_INCR_OP    => self::EXPRESSION,
				self::TYPE_ADD_OP     => self::EXPRESSION,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION,
				self::TYPE_RETURN     => self::EXPRESSION_NO_NL,
				self::TYPE_IF         => self::CONDITION,
				self::TYPE_FUNC       => self::CONDITION,
				self::TYPE_LITERAL    => self::EXPRESSION_OP
			],
			self::CONDITION => [
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION
			],
			self::PROPERTY_ASSIGNMENT => [
				self::TYPE_COLON      => self::PROPERTY_EXPRESSION,
				self::TYPE_BRACE_OPEN => self::STATEMENT
			],
			self::EXPRESSION => [
				self::TYPE_SEMICOLON  => self::STATEMENT,
				self::TYPE_BRACE_OPEN => self::PROPERTY_ASSIGNMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION,
				self::TYPE_FUNC       => self::EXPRESSION_FUNC,
				self::TYPE_LITERAL    => self::EXPRESSION_OP
			],
			self::EXPRESSION_NO_NL => [
				self::TYPE_SEMICOLON  => self::STATEMENT,
				self::TYPE_BRACE_OPEN => self::PROPERTY_ASSIGNMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION,
				self::TYPE_FUNC       => self::EXPRESSION_FUNC,
				self::TYPE_LITERAL    => self::EXPRESSION_OP
			],
			self::EXPRESSION_OP => [
				self::TYPE_BIN_OP     => self::EXPRESSION,
				self::TYPE_ADD_OP     => self::EXPRESSION,
				self::TYPE_HOOK       => self::EXPRESSION_TERNARY,
				self::TYPE_COLON      => self::STATEMENT,
				self::TYPE_COMMA      => self::EXPRESSION,
				self::TYPE_SEMICOLON  => self::STATEMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION
			],
			self::EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => self::STATEMENT
			],
			self::EXPRESSION_TERNARY => [
				self::TYPE_BRACE_OPEN => self::PROPERTY_ASSIGNMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION,
				self::TYPE_FUNC       => self::EXPRESSION_TERNARY_FUNC,
				self::TYPE_LITERAL    => self::EXPRESSION_TERNARY_OP
			],
			self::EXPRESSION_TERNARY_OP => [
				self::TYPE_BIN_OP     => self::EXPRESSION_TERNARY,
				self::TYPE_ADD_OP     => self::EXPRESSION_TERNARY,
				self::TYPE_HOOK       => self::EXPRESSION_TERNARY,
				self::TYPE_COMMA      => self::EXPRESSION_TERNARY,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION
			],
			self::EXPRESSION_TERNARY_FUNC => [
				self::TYPE_BRACE_OPEN => self::STATEMENT
			],
			self::PAREN_EXPRESSION => [
				self::TYPE_BRACE_OPEN => self::PROPERTY_ASSIGNMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION,
				self::TYPE_FUNC       => self::PAREN_EXPRESSION_FUNC,
				self::TYPE_LITERAL    => self::PAREN_EXPRESSION_OP
			],
			self::PAREN_EXPRESSION_OP => [
				self::TYPE_BIN_OP     => self::PAREN_EXPRESSION,
				self::TYPE_ADD_OP     => self::PAREN_EXPRESSION,
				self::TYPE_HOOK       => self::PAREN_EXPRESSION,
				self::TYPE_COLON      => self::PAREN_EXPRESSION,
				self::TYPE_COMMA      => self::PAREN_EXPRESSION,
				self::TYPE_SEMICOLON  => self::PAREN_EXPRESSION,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION
			],
			self::PAREN_EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => self::STATEMENT
			],
			self::PROPERTY_EXPRESSION => [
				self::TYPE_BRACE_OPEN => self::PROPERTY_ASSIGNMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION,
				self::TYPE_FUNC       => self::PROPERTY_EXPRESSION_FUNC,
				self::TYPE_LITERAL    => self::PROPERTY_EXPRESSION_OP
			],
			self::PROPERTY_EXPRESSION_OP => [
				self::TYPE_BIN_OP     => self::PROPERTY_EXPRESSION,
				self::TYPE_ADD_OP     => self::PROPERTY_EXPRESSION,
				self::TYPE_HOOK       => self::PROPERTY_EXPRESSION,
				self::TYPE_COMMA      => self::PROPERTY_ASSIGNMENT,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION
			],
			self::PROPERTY_EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => self::STATEMENT
			]
		];

		// $push : This table contains the rules for when to push a state onto the stack.
		//         The pushed state is the state to return to when the corresponding
		//         closing token is found
		$push = [
			self::STATEMENT => [
				self::TYPE_BRACE_OPEN => self::STATEMENT,
				self::TYPE_PAREN_OPEN => self::EXPRESSION_OP
			],
			self::CONDITION => [
				self::TYPE_PAREN_OPEN => self::STATEMENT
			],
			self::PROPERTY_ASSIGNMENT => [
				self::TYPE_BRACE_OPEN => self::PROPERTY_ASSIGNMENT
			],
			self::EXPRESSION => [
				self::TYPE_BRACE_OPEN => self::EXPRESSION_OP,
				self::TYPE_PAREN_OPEN => self::EXPRESSION_OP
			],
			self::EXPRESSION_NO_NL => [
				self::TYPE_BRACE_OPEN => self::EXPRESSION_OP,
				self::TYPE_PAREN_OPEN => self::EXPRESSION_OP
			],
			self::EXPRESSION_OP => [
				self::TYPE_HOOK       => self::EXPRESSION,
				self::TYPE_PAREN_OPEN => self::EXPRESSION_OP
			],
			self::EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => self::EXPRESSION_OP
			],
			self::EXPRESSION_TERNARY => [
				self::TYPE_BRACE_OPEN => self::EXPRESSION_TERNARY_OP,
				self::TYPE_PAREN_OPEN => self::EXPRESSION_TERNARY_OP
			],
			self::EXPRESSION_TERNARY_OP => [
				self::TYPE_HOOK       => self::EXPRESSION_TERNARY,
				self::TYPE_PAREN_OPEN => self::EXPRESSION_TERNARY_OP
			],
			self::EXPRESSION_TERNARY_FUNC => [
				self::TYPE_BRACE_OPEN => self::EXPRESSION_TERNARY_OP
			],
			self::PAREN_EXPRESSION => [
				self::TYPE_BRACE_OPEN => self::PAREN_EXPRESSION_OP,
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION_OP
			],
			self::PAREN_EXPRESSION_OP => [
				self::TYPE_PAREN_OPEN => self::PAREN_EXPRESSION_OP
			],
			self::PAREN_EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => self::PAREN_EXPRESSION_OP
			],
			self::PROPERTY_EXPRESSION => [
				self::TYPE_BRACE_OPEN => self::PROPERTY_EXPRESSION_OP,
				self::TYPE_PAREN_OPEN => self::PROPERTY_EXPRESSION_OP
			],
			self::PROPERTY_EXPRESSION_OP => [
				self::TYPE_PAREN_OPEN => self::PROPERTY_EXPRESSION_OP
			],
			self::PROPERTY_EXPRESSION_FUNC => [
				self::TYPE_BRACE_OPEN => self::PROPERTY_EXPRESSION_OP
			]
		];

		// $pop : Rules for when to pop a state from the stack
		$pop = [
			self::STATEMENT              => [ self::TYPE_BRACE_CLOSE => true ],
			self::PROPERTY_ASSIGNMENT    => [ self::TYPE_BRACE_CLOSE => true ],
			self::EXPRESSION             => [ self::TYPE_BRACE_CLOSE => true ],
			self::EXPRESSION_NO_NL       => [ self::TYPE_BRACE_CLOSE => true ],
			self::EXPRESSION_OP          => [ self::TYPE_BRACE_CLOSE => true ],
			self::EXPRESSION_TERNARY_OP  => [ self::TYPE_COLON       => true ],
			self::PAREN_EXPRESSION       => [ self::TYPE_PAREN_CLOSE => true ],
			self::PAREN_EXPRESSION_OP    => [ self::TYPE_PAREN_CLOSE => true ],
			self::PROPERTY_EXPRESSION    => [ self::TYPE_BRACE_CLOSE => true ],
			self::PROPERTY_EXPRESSION_OP => [ self::TYPE_BRACE_CLOSE => true ]
		];

		// $semicolon : Rules for when a semicolon insertion is appropriate
		$semicolon = [
			self::EXPRESSION_NO_NL => [
				self::TYPE_UN_OP      => true,
				self::TYPE_INCR_OP    => true,
				self::TYPE_ADD_OP     => true,
				self::TYPE_BRACE_OPEN => true,
				self::TYPE_PAREN_OPEN => true,
				self::TYPE_RETURN     => true,
				self::TYPE_IF         => true,
				self::TYPE_DO         => true,
				self::TYPE_FUNC       => true,
				self::TYPE_LITERAL    => true
			],
			self::EXPRESSION_OP => [
				self::TYPE_UN_OP      => true,
				self::TYPE_INCR_OP    => true,
				self::TYPE_BRACE_OPEN => true,
				self::TYPE_RETURN     => true,
				self::TYPE_IF         => true,
				self::TYPE_DO         => true,
				self::TYPE_FUNC       => true,
				self::TYPE_LITERAL    => true
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
			$type = isset( $tokenTypes[$token] ) ? $tokenTypes[$token] : self::TYPE_LITERAL;

			if ( $newlineFound && isset( $semicolon[$state][$type] ) ) {
				// This token triggers the semicolon insertion mechanism of javascript. While we
				// could add the ; token here ourselves, keeping the newline has a few advantages.
				$out .= "\n";
				$state = self::STATEMENT;
				$lineLength = 0;
			} elseif ( $lineLength + $end - $pos > self::MAX_LINE_LENGTH &&
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
			if ( isset( $push[$state][$type] ) && count( $stack ) < self::STACK_LIMIT ) {
				$stack[] = $push[$state][$type];
			}
			if ( $stack && isset( $pop[$state][$type] ) ) {
				$state = array_pop( $stack );
			} elseif ( isset( $goto[$state][$type] ) ) {
				$state = $goto[$state][$type];
			}
		}
		return $out;
	}

	static function parseError( $fullJavascript, $position, $errorMsg ) {
		// TODO: Handle the error: trigger_error, throw exception, return false...
		return false;
	}
}
