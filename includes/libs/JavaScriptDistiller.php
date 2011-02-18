<?php
/**
 * JavaScript Distiller
 *
 * Author: Dean Edwards, Nicholas Martin, Trevor Parscal
 * License: LGPL
 */
class JavaScriptDistiller {

	/* Static Methods */

	/**
	 * Removes most of the white-space from JavaScript code.
	 *
	 * This code came from the first pass of Dean Edwards' JavaScript Packer.  Compared to using
	 * JSMin::minify, this produces < 1% larger output (after gzip) in approx. 25% of the time.
	 *
	 * @param $script String: JavaScript code to minify
	 * @param $stripVerticalSpace Boolean: Try to remove as much vertical whitespace as possible
	 */
	public static function stripWhiteSpace( $script, $stripVerticalSpace = false ) {
		// Try to avoid segfaulting
		// I saw segfaults with a limit of 10000, 1000 seems to work
		$oldLimit = ini_get( 'pcre.recursion_limit' );
		if ( intval( $oldLimit ) > 1000 ) {
			ini_set( 'pcre.recursion_limit', '1000' );
		}

		$script = self::stripHorizontalSpace( $script );
		// If requested, make some vertical whitespace collapsing as well
		if ( $stripVerticalSpace ) {
			$script = self::stripVerticalSpace( $script );
		}
		// Done
		ini_set( 'pcre.recursion_limit', $oldLimit );
		return $script;
	}

	public static function stripHorizontalSpace( $script ) {
		$parser = self::createParser();
		// Collapse horizontal whitespaces between variable names into a single space
		$parser->add( '(\b|\$) [ \t]+ (\b|\$)', '$2 $3' );
		// Collapse horizontal whitespaces between unary operators into a single space
		$parser->add( '([+\-]) [ \t]+ ([+\-])', '$2 $3' );
		// Remove all remaining un-protected horizontal whitespace
		$parser->add( '[ \t]+');
		// Collapse multiple vertical whitespaces with some horizontal spaces between them
		$parser->add( '[\r\n]+ [ \t]* [\r\n]+', "\n" );
		// Execute and return
		return $parser->exec($script);
	}

	public static function stripVerticalSpace( $script ) {
		$parser = self::createParser();
		// Collapse whitespaces between and after a ){ pair (function definitions)
		$parser->add( '\) \s+ \{ \s+', '){' );
		// Collapse whitespaces between and after a ({ pair (JSON argument)
		$parser->add( '\( \s+ \{ \s+', '({' );
		// Collapse whitespaces between a parenthesis and a period (call chaining)
		$parser->add( '\) \s+ \.', ').');
		// Collapse vertical whitespaces which come directly after a semicolon or a comma
		$parser->add( '( [;,] ) \s+', '$2' );
		// Collapse whitespaces between multiple parenthesis/brackets of similar direction
		$parser->add( '( [\)\}] ) \s+ ( [\)\}] )', '$2$3' );
		$parser->add( '( [\(\{] ) \s+ ( [\(\{] )', '$2$3' );
		return $parser->exec( $script );
	}

	/*
	 * Creates an instance of ParseMaster and protects sensitive JavaScript regions.
	 *
	 * This parser is based on regular expressions, which all get or'd together, so rules take
	 * precedence in the order they are added. We can use it to minify by armoring certain regions
	 * by matching them and replacing them with the full match, leaving the remaining regions around
	 * for further matching and replacing. When creating rules please note that because ParseMaster
	 * "or"s all of the rules together in a single pattern, encapsulating them in parenthesis, $1
	 * represents the whole match for a given rule, and $2 is the first submatch.
	 */
	private static function createParser() {
		$parser = new ParseMaster();
		// There is a bug in ParseMaster that causes a backslash at the end of a line to be changed
		// to \s if we use a backslash as the escape character. We work around this by using an
		// obscure escape character that we hope will never appear at the end of a line.
		$parser->escapeChar = chr( 1 );
		
		// C-style comment: use non-greedy repetition to find the end
		$parser->add( '\/ \* .*? \* \/' );

		// Preserve the newline after a C++-style comment -- bug 27046
		$parser->add( '\/ \/ [^\r\n]* ( [\r\n] )', '$2' );

		// Protect strings. The original code had [^\'\\v] here, but that didn't armor multiline
		// strings correctly. This also armors multiline strings that don't have backslashes at the
		// end of the line (these are invalid), but that's fine because we're just armoring here.

		// Single quotes
		$parser->add(
			'\'' . // start quote
			'[^\'\\\\]*' . // a run of non-special characters
			'(?:' .
				'\\\\ .' . // a backslash followed by a character or line ending
				'[^\'\\\\]*' . // a run of non-special characters
			')*' . // any number of the above
			'\'', // end quote
			'$1' );

		// Double quotes: same as above
		$parser->add( '" [^"\\\\]* (?: \\\\ . [^"\\\\]* )* "', '$1' );

		// Protect regular expressions
		// Regular expression with whitespace before it
		$parser->add(
			'(?<= [ \t] | [^\w\$\/\'"*)\?:] )' . // assert that whitespace or punctuation precedes
			'\/' . // start slash
			'[^\r\n\*]' . // not a comment-start or line ending
			'[^\/\r\n\\\\]*' . // a sequence of non-special characters
			'(?:' . 
				'\\\\.' . // an escaped dot
				'[^\/\r\n\\\\]*' . // a sequence of non-special characters
			')*' . // any number of the above
			'\/[ig]*' , // pattern end, optional modifier
			'$1' );
		return $parser;
	}
}

/**
 * ParseMaster, version 1.0.2 (2005-08-19) Copyright 2005, Dean Edwards
 * A multi-pattern parser.
 * License: http://creativecommons.org/licenses/LGPL/2.1/
 *
 * This is the PHP version of the ParseMaster component of Dean Edwards' (http://dean.edwards.name/)
 * Packer, which was originally written in JavaScript. It was ported to PHP by Nicolas Martin.
 *
 * Original Source: http://joliclic.free.fr/php/javascript-packer/en/
 *
 * Changes should be pushed back upstream.
 */
class ParseMaster {
	public $ignoreCase = false;
	public $escapeChar = '';

	// constants
	const EXPRESSION = 0;
	const REPLACEMENT = 1;
	const LENGTH = 2;

	// used to determine nesting levels
	private $GROUPS = '/\( (?! \? ) /x';//g
	private $SUB_REPLACE = '/\$\d/';
	private $INDEXED = '/^\$\d+$/';
	private $ESCAPE = '/\\\./';//g
	private $QUOTE = '/\'/';
	private $DELETED = '/\x01[^\x01]*\x01/';//g

	public function add($expression, $replacement = '') {
		// count the number of sub-expressions
		//  - add one because each pattern is itself a sub-expression
		$length = 1 + preg_match_all($this->GROUPS, $this->_internalEscape((string)$expression), $out);

		// treat only strings $replacement
		if (is_string($replacement)) {
			// does the pattern deal with sub-expressions?
			if (preg_match($this->SUB_REPLACE, $replacement)) {
				// a simple lookup? (e.g. "$2")
				if (preg_match($this->INDEXED, $replacement)) {
					// store the index (used for fast retrieval of matched strings)
					$replacement = (int)(substr($replacement, 1)) - 1;
				} else { // a complicated lookup (e.g. "Hello $2 $1")
					// build a function to do the lookup
					$quote = preg_match($this->QUOTE, $this->_internalEscape($replacement))
							 ? '"' : "'";
					$replacement = array(
						'fn' => '_backReferences',
						'data' => array(
							'replacement' => $replacement,
							'length' => $length,
							'quote' => $quote
						)
					);
				}
			}
		}
		// pass the modified arguments
		if (!empty($expression)) $this->_add($expression, $replacement, $length);
		else $this->_add('/^$/', $replacement, $length);
	}

	public function exec($string) {
		// execute the global replacement
		$this->_escaped = array();

		// simulate the _patterns.toSTring of Dean
		$regexp = '/';
		foreach ($this->_patterns as $reg) {
			$regexp .= '(' . $reg[self::EXPRESSION] . ")|\n";
		}
		$regexp = substr($regexp, 0, -2) . '/Sxs';
		$regexp .= ($this->ignoreCase) ? 'i' : '';

		$string = $this->_escape($string, $this->escapeChar);
		$string = preg_replace_callback(
			$regexp,
			array(
				&$this,
				'_replacement'
			),
			$string
		);
		$string = $this->_unescape($string, $this->escapeChar);

		return preg_replace($this->DELETED, '', $string);
	}

	public function reset() {
		// clear the patterns collection so that this object may be re-used
		$this->_patterns = array();
	}

	// private
	private $_escaped = array();  // escaped characters
	private $_patterns = array(); // patterns stored by index

	// create and add a new pattern to the patterns collection
	private function _add() {
		$arguments = func_get_args();
		$this->_patterns[] = $arguments;
	}

	// this is the global replace function (it's quite complicated)
	private function _replacement($arguments) {
		if (empty($arguments)) return '';

		$i = 1; $j = 0;
		// loop through the patterns
		while (isset($this->_patterns[$j])) {
			$pattern = $this->_patterns[$j++];
			// do we have a result?
			if (isset($arguments[$i]) && ($arguments[$i] != '')) {
				$replacement = $pattern[self::REPLACEMENT];

				if (is_array($replacement) && isset($replacement['fn'])) {

					if (isset($replacement['data'])) $this->buffer = $replacement['data'];
					return call_user_func(array(&$this, $replacement['fn']), $arguments, $i);

				} elseif (is_int($replacement)) {
					return $arguments[$replacement + $i];

				}
				$delete = ($this->escapeChar == '' ||
						   strpos($arguments[$i], $this->escapeChar) === false)
						? '' : "\x01" . $arguments[$i] . "\x01";
				return $delete . $replacement;

			// skip over references to sub-expressions
			} else {
				$i += $pattern[self::LENGTH];
			}
		}
	}

	private function _backReferences($match, $offset) {
		$replacement = $this->buffer['replacement'];
		//$quote = $this->buffer['quote'];
		$i = $this->buffer['length'];
		while ($i) {
			$replacement = str_replace('$'.$i--, $match[$offset + $i], $replacement);
		}
		return $replacement;
	}

	private function _replace_name($match, $offset){
		$length = strlen($match[$offset + 2]);
		$start = $length - max($length - strlen($match[$offset + 3]), 0);
		return substr($match[$offset + 1], $start, $length) . $match[$offset + 4];
	}

	private function _replace_encoded($match, $offset) {
		return $this->buffer[$match[$offset]];
	}


	// php : we cannot pass additional data to preg_replace_callback,
	// and we cannot use &$this in create_function, so let's go to lower level
	private $buffer;

	// encode escaped characters
	private function _escape($string, $escapeChar) {
		if ($escapeChar) {
			$this->buffer = $escapeChar;
			return preg_replace_callback(
				'/\\' . $escapeChar . '(.)' .'/',
				array(&$this, '_escapeBis'),
				$string
			);

		} else {
			return $string;
		}
	}
	private function _escapeBis($match) {
		$this->_escaped[] = $match[1];
		return $this->buffer;
	}

	// decode escaped characters
	private function _unescape($string, $escapeChar) {
		if ($escapeChar) {
			$regexp = '/'.'\\'.$escapeChar.'/';
			$this->buffer = array('escapeChar'=> $escapeChar, 'i' => 0);
			return preg_replace_callback
			(
				$regexp,
				array(&$this, '_unescapeBis'),
				$string
			);

		} else {
			return $string;
		}
	}
	private function _unescapeBis() {
		if (isset($this->_escaped[$this->buffer['i']])
			&& $this->_escaped[$this->buffer['i']] != '')
		{
			 $temp = $this->_escaped[$this->buffer['i']];
		} else {
			$temp = '';
		}
		$this->buffer['i']++;
		return $this->buffer['escapeChar'] . $temp;
	}

	private function _internalEscape($string) {
		return preg_replace($this->ESCAPE, '', $string);
	}
}
