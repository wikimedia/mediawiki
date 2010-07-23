<?php
/**
 * Spyc -- A Simple PHP YAML Class
 * @version 0.2.3 -- 2006-02-04
 * @author Chris Wanstrath <chris@ozmm.org>
 * @see http://spyc.sourceforge.net/
 * @copyright Copyright 2005-2006 Chris Wanstrath
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * The Simple PHP YAML Class.
 *
 * This class can be used to read a YAML file and convert its contents
 * into a PHP array.  It currently supports a very limited subsection of
 * the YAML spec.
 *
 * @ingroup API
 */
class Spyc {

	/**
	 * Dump YAML from PHP array statically
	 *
	 * The dump method, when supplied with an array, will do its best
	 * to convert the array into friendly YAML.  Pretty simple.  Feel free to
	 * save the returned string as nothing.yml and pass it around.
	 *
	 * Oh, and you can decide how big the indent is and what the wordwrap
	 * for folding is.  Pretty cool -- just pass in 'false' for either if
	 * you want to use the default.
	 *
	 * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
	 * you can turn off wordwrap by passing in 0.
	 *
	 * @param $array Array: PHP array
	 * @param $indent Integer: Pass in false to use the default, which is 2
	 * @param $wordwrap Integer: Pass in 0 for no wordwrap, false for default (40)
	 * @return String
	 */
	public static function YAMLDump( $array, $indent = false, $wordwrap = false ) {
		$spyc = new Spyc;
		return $spyc->dump( $array, $indent, $wordwrap );
	}

	/**
	 * Dump PHP array to YAML
	 *
	 * The dump method, when supplied with an array, will do its best
	 * to convert the array into friendly YAML.  Pretty simple.  Feel free to
	 * save the returned string as tasteful.yml and pass it around.
	 *
	 * Oh, and you can decide how big the indent is and what the wordwrap
	 * for folding is.  Pretty cool -- just pass in 'false' for either if
	 * you want to use the default.
	 *
	 * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
	 * you can turn off wordwrap by passing in 0.
	 *
	 * @param $array Array: PHP array
	 * @param $indent Integer: Pass in false to use the default, which is 2
	 * @param $wordwrap Integer: Pass in 0 for no wordwrap, false for default (40)
	 * @return String
	 */
	public function dump( $array, $indent = false, $wordwrap = false ) {
		// Dumps to some very clean YAML.  We'll have to add some more features
		// and options soon.  And better support for folding.

		// New features and options.
		if ( $indent === false or !is_numeric( $indent ) ) {
			$this->_dumpIndent = 2;
		} else {
			$this->_dumpIndent = $indent;
		}

		if ( $wordwrap === false or !is_numeric( $wordwrap ) ) {
			$this->_dumpWordWrap = 40;
		} else {
			$this->_dumpWordWrap = $wordwrap;
		}

		// New YAML document
		$string = "---\n";

		// Start at the base of the array and move through it.
		foreach ( $array as $key => $value ) {
			$string .= $this->_yamlize( $key, $value, 0 );
		}
		return $string;
	}

	/**** Private Properties ****/

	/**
	private $_haveRefs;
	private $_allNodes;
	private $_lastIndent;
	private $_lastNode;
	private $_inBlock;
	private $_isInline;
	**/
	private $_dumpIndent;
	private $_dumpWordWrap;

	/**** Private Methods ****/

	/**
	 * Attempts to convert a key / value array item to YAML
	 *
	 * @param $key Mixed: the name of the key
	 * @param $value Mixed: the value of the item
	 * @param $indent Integer: the indent of the current node
	 * @return String
	 */
	private function _yamlize( $key, $value, $indent ) {
		if ( is_array( $value ) ) {
			// It has children.  What to do?
			// Make it the right kind of item
			$string = $this->_dumpNode( $key, null, $indent );
			// Add the indent
			$indent += $this->_dumpIndent;
			// Yamlize the array
			$string .= $this->_yamlizeArray( $value, $indent );
		} elseif ( !is_array( $value ) ) {
			// It doesn't have children.  Yip.
			$string = $this->_dumpNode( $key, $value, $indent );
		}
		return $string;
	}

	/**
	 * Attempts to convert an array to YAML
	 *
	 * @param $array Array: the array you want to convert
	 * @param $indent Integer: the indent of the current level
	 * @return String
	 */
	private function _yamlizeArray( $array, $indent ) {
		if ( is_array( $array ) ) {
			$string = '';
			foreach ( $array as $key => $value ) {
				$string .= $this->_yamlize( $key, $value, $indent );
			}
			return $string;
		} else {
			return false;
		}
    }

	/**
	 * Find out whether a string needs to be output as a literal rather than in plain style.
	 * Added by Roan Kattouw 13-03-2008
	 *
	 * @param $value String: the string to check
	 * @return Boolean
	 */
	function _needLiteral( $value ) {
		// Check whether the string contains # or : or begins with any of:
		// [ - ? , [ ] { } ! * & | > ' " % @ ` ]
		// or is a number or contains newlines
		return (bool)( gettype( $value ) == "string" &&
			( is_numeric( $value )  ||
			strpos( $value, "\n" ) ||
			preg_match( "/[#:]/", $value ) ||
			preg_match( "/^[-?,[\]{}!*&|>'\"%@`]/", $value ) ) );
	}

	/**
	 * Returns YAML from a key and a value
	 *
	 * @param $key Mixed: the name of the key
	 * @param $value Mixed: the value of the item
	 * @param $indent Integer: the indent of the current node
	 * @return String
	 */
	private function _dumpNode( $key, $value, $indent ) {
		// do some folding here, for blocks
		if ( $this->_needLiteral( $value ) ) {
			$value = $this->_doLiteralBlock( $value, $indent );
		} else {
			$value = $this->_doFolding( $value, $indent );
		}

		$spaces = str_repeat( ' ', $indent );

		if ( is_int( $key ) ) {
			// It's a sequence
			if ( $value !== '' && !is_null( $value ) )
				$string = $spaces . '- ' . $value . "\n";
			else
				$string = $spaces . "-\n";
		} else {
			 if ( $key == '*' ) // bug 21922 - Quote asterix used as keys
				$key = "'*'";

			// It's mapped
			if ( $value !== '' && !is_null( $value ) )
				$string = $spaces . $key . ': ' . $value . "\n";
			else
				$string = $spaces . $key . ":\n";
		}
		return $string;
	}

	/**
	 * Creates a literal block for dumping
	 *
	 * @param $value String
	 * @param $indent Integer: the value of the indent
	 * @return String
	 */
	private function _doLiteralBlock( $value, $indent ) {
		$exploded = explode( "\n", $value );
		$newValue = '|-';
		$indent  += $this->_dumpIndent;
		$spaces   = str_repeat( ' ', $indent );
		foreach ( $exploded as $line ) {
			$newValue .= "\n" . $spaces . trim( $line );
		}
		return $newValue;
	}

	/**
	 * Folds a string of text, if necessary
	 *
	 * @param $value String: the string you wish to fold
	 * @param $indent Integer: the indent of the current node
	 * @return String
	 */
	private function _doFolding( $value, $indent ) {
		// Don't do anything if wordwrap is set to 0
		if ( $this->_dumpWordWrap === 0 ) {
			return $value;
		}

		if ( strlen( $value ) > $this->_dumpWordWrap ) {
			$indent += $this->_dumpIndent;
			$indent = str_repeat( ' ', $indent );
			$wrapped = wordwrap( $value, $this->_dumpWordWrap, "\n$indent" );
			$value   = ">-\n" . $indent . $wrapped;
		}
		return $value;
	}
}
