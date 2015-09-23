<?php
/**
 * Verify specific control structures are followed by a single space.
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_WhiteSpace_SpaceAfterControlStructureSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		// Per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Spaces
		return array(
			T_IF,
			T_WHILE,
			T_FOR,
			T_FOREACH,
			T_SWITCH,
			T_CATCH,
		);
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$nextToken = $tokens[$stackPtr + 1];
		if ( $nextToken['code'] !== T_WHITESPACE || $nextToken['content'] !== ' ' ) {
			$error = 'Control structure "%s" must be followed by a single space';
			$data = array( $tokens[$stackPtr]['content'] );
			$phpcsFile->addWarning( $error, $stackPtr, 'Incorrect', $data );
		}
	}
}
