<?php
/**
 * Make sure calling functions is spacey:
 * $this->foo( $arg, $arg2 );
 * wfFoo( $arg, $arg2 );
 *
 * But, wfFoo() is ok.
 *
 * Also disallow wfFoo( ) and wfFoo(  $param )
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_WhiteSpace_SpaceyParenthesisSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return array(
			T_OPEN_PARENTHESIS,
			T_CLOSE_PARENTHESIS,
		);
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$currentToken = $tokens[$stackPtr];

		if ( $currentToken['code'] === T_OPEN_PARENTHESIS
			&& $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
			&& ( $tokens[$stackPtr - 2]['code'] === T_STRING
				|| $tokens[$stackPtr - 2]['code'] === T_ARRAY ) ) {
			// String (or 'array') followed by whitespace followed by
			// opening brace is probably a function call.
			$phpcsFile->addWarning(
				'Space found before opening parenthesis of function call',
				$stackPtr - 1,
				'SpaceBeforeOpeningParenthesis'
			);
		}

		// Check for space between parentheses without any arguments
		if ( $currentToken['code'] === T_OPEN_PARENTHESIS
			&& $tokens[$stackPtr + 1]['code'] === T_WHITESPACE
			&& $tokens[$stackPtr + 2]['code'] === T_CLOSE_PARENTHESIS ) {
			$phpcsFile->addWarning(
				'Unnecessary space found within parentheses',
				$stackPtr + 1,
				'UnnecessarySpaceBetweenParentheses'
			);
			return;
		}

		// Same check as above, but ignore since it was already processed
		if ( $currentToken['code'] === T_CLOSE_PARENTHESIS
			&& $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
			&& $tokens[$stackPtr - 2]['code'] === T_OPEN_PARENTHESIS ) {
			return;
		}

		if ( $currentToken['code'] === T_OPEN_PARENTHESIS ) {
			$this->processOpenParenthesis( $phpcsFile, $tokens, $stackPtr );
		} else {
			// T_CLOSE_PARENTHESIS
			$this->processCloseParenthesis( $phpcsFile, $tokens, $stackPtr );
		}
	}

	protected function processOpenParenthesis( PHP_CodeSniffer_File $phpcsFile, $tokens, $stackPtr ) {
		$nextToken = $tokens[$stackPtr + 1];
		// No space or not single space
		if ( ( $nextToken['code'] === T_WHITESPACE &&
				strpos( $nextToken['content'], "\n" ) === false
				&& $nextToken['content'] != ' ' )
			|| ( $nextToken['code'] !== T_CLOSE_PARENTHESIS && $nextToken['code'] !== T_WHITESPACE ) ) {
			$phpcsFile->addWarning(
				'Single space expected after opening parenthesis',
				$stackPtr + 1,
				'SingleSpaceAfterOpenParenthesis'
			);
		}
	}

	protected function processCloseParenthesis( PHP_CodeSniffer_File $phpcsFile, $tokens, $stackPtr ) {
		$previousToken = $tokens[$stackPtr - 1];

		if ( $previousToken['code'] === T_OPEN_PARENTHESIS
			|| ( $previousToken['code'] === T_WHITESPACE
				&& $previousToken['content'] === ' ' )
			|| ( $previousToken['code'] === T_COMMENT
				&& substr( $previousToken['content'], -1, 1 ) === "\n" ) ) {
			// If previous token was
			// '(' or ' ' or a comment ending with a newline
			return;
		}

		// If any of the whitespace tokens immediately before this token have a newline
		$ptr = $stackPtr - 1;
		while ( $tokens[$ptr]['code'] === T_WHITESPACE ) {
			if ( strpos( $tokens[$ptr]['content'], "\n" ) !== false ) {
				return;
			}
			$ptr--;
		}

		// If the comment before all the whitespaces immediately preceding the ')' ends with a newline
		if ( $tokens[$ptr]['code'] === T_COMMENT
			&& substr( $tokens[$ptr]['content'], -1, 1 ) === "\n" ) {
			return;
		}

		$phpcsFile->addWarning(
			'Single space expected before closing parenthesis',
			$stackPtr,
			'SingleSpaceBeforeCloseParenthesis'
		);
	}
}
