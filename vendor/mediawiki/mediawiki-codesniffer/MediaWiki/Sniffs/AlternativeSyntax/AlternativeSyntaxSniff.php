<?php
/**
 * Verify alternative syntax is not being used
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_AlternativeSyntax_AlternativeSyntaxSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		// Per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP
		// section on alternative syntax.
		return array(
			T_ENDDECLARE,
			T_ENDFOR,
			T_ENDFOREACH,
			T_ENDIF,
			T_ENDSWITCH,
			T_ENDWHILE,
		);
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$error = 'Alternative syntax such as "%s" should not be used';
		$data = array( $tokens[$stackPtr]['content'] );
		$phpcsFile->addWarning( $error, $stackPtr, 'AlternativeSyntax', $data );
	}
}
