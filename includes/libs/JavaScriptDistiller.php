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
	 */
	public static function stripWhiteSpace( $script, $collapseVertical = false ) {
		// This parser is based on regular expressions, which all get or'd together, so rules take
		// precedence in the order they are added. We can use it to minify by armoring certain
		// regions by matching them and replacing them with the full match, leaving the remaining
		// regions around for further matching and replacing.
		$parser = new ParseMaster();
		// There is a bug in ParseMaster that causes a backslash at the end of a line to be changed
		// to \s if we use a backslash as the escape character. We work around this by using an
		// obscure escape character that we hope will never appear at the end of a line.
		$parser->escapeChar = chr( 1 );
		// Protect strings. The original code had [^\'\\v] here, but that didn't armor multiline
		// strings correctly. This also armors multiline strings that don't have backslashes at the
		// end of the line (these are invalid), but that's fine because we're just armoring here.
		$parser->add('/\'[^\']*\'/', '$1' );
		$parser->add('/"[^"]*"/', '$1' );
		// Remove comments
		$parser->add('/\\/\\/[^\v]*[\v]/', ' ');
		$parser->add('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', ' ');
		// Protect regular expressions
		$parser->add('/\\h+(\\/[^\\/\\v\\*][^\\/\\v]*\\/g?i?)/', '$2'); // IGNORE
		$parser->add('/[^\\w\\x24\\/\'"*)\\?:]\\/[^\\/\\v\\*][^\\/\\v]*\\/g?i?/', '$1');
		// Remove: ;;; doSomething();
		$parser->add('/;;;[^\\v]+[\\v]/');
		// Remove redundant semi-colons
		$parser->add('/\\(;;\\)/', '$1'); // protect for (;;) loops
		$parser->add('/;+\\h*([};])/', '$2');
		// Apply all rules defined up to this point
		$script = $parser->exec($script);
		// If requested, make some vertical whitespace collapsing as well
		if ( $collapseVertical ) {
			// Collapse whitespaces between and after a ){ pair (function definitions)
			$parser->add('/\\)\\s+\\{\\s+/', '){');
			// Collapse whitespaces between and after a ({ pair (JSON argument)
			$parser->add('/\\(\\s+\\{\\s+/', '({');
			// Collapse whitespaces between a parenthesis and a period (call chaining)
			$parser->add('/\\)\\s+\\./', ').');
			// Collapse vertical whitespaces which come directly after a semicolon or a comma
			$parser->add('/([;,])\\s+/', '$2');
			// Collapse whitespaces between multiple parenthesis/brackets of similar direction
			$parser->add('/([\\)\\}])\\s+([\\)\\}])/', '$2$3');
			$parser->add('/([\\(\\{])\\s+([\\(\\{])/', '$2$3');
		}
		// Collapse horizontal whitespaces between variable names into a single space
		$parser->add('/(\\b|\\x24)\\h+(\\b|\\x24)/', '$2 $3');
		// Collapse horizontal whitespaces between urinary operators into a single space
		$parser->add('/([+\\-])\\h+([+\\-])/', '$2 $3');
		// Collapse all remaining un-protected horizontal whitespace
		$parser->add('/\\h+/', '');
		// Collapse multiple vertical whitespaces with some horizontal spaces between them
		$parser->add('/\\v+\\h*\\v*/', "\n");
		
		// Done
		return $parser->exec($script);
		
	}
}
