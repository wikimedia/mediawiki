<?php
/**
 * Report error when `goto` is used
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_GotoUsage_GotoUsageSniff implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		// As per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Other
		return array(
			T_GOTO
		);
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$error = 'Control statement "goto" must not be used.';
		$phpcsFile->addError( $error, $stackPtr, 'GotoUsage' );
	}
}
