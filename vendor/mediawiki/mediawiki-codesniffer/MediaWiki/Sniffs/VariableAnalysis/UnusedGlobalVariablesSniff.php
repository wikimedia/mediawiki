<?php
/**
 * Detect unused MediaWiki global variable.
 * Unused global variables should be removed.
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_VariableAnalysis_UnusedGlobalVariablesSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return array( T_FUNCTION );
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if ( !isset( $tokens[$stackPtr]['scope_opener'] ) ) {
			// An interface or abstract function which doesn't have a body
			return;
		}
		$scopeOpener = ++$tokens[$stackPtr]['scope_opener'];
		$scopeCloser = $tokens[$stackPtr]['scope_closer'];

		$globalLine = 0;
		$globalVariables = array();
		$otherVariables = array();
		$matches = array();
		$strVariables = array();

		for ( $i = $scopeOpener; $i < $scopeCloser; $i++ ) {
			if ( in_array( $tokens[$i]['type'], PHP_CodeSniffer_Tokens::$emptyTokens ) ) {
				continue;
			}
			if ( $tokens[$i]['type'] === 'T_GLOBAL' ) {
				$globalLine = $tokens[$i]['line'];
			}
			if ( $tokens[$i]['type'] === 'T_VARIABLE' && $tokens[$i]['line'] == $globalLine ) {
				$globalVariables[] = $tokens[$i]['content'] .'#'. $i;
			}
			if ( $tokens[$i]['type'] === 'T_VARIABLE' && $tokens[$i]['line'] != $globalLine ) {
				$otherVariables[] = $tokens[$i]['content'];
			}
			if ( $tokens[$i]['type'] === 'T_DOUBLE_QUOTED_STRING' || $tokens[$i]['type'] === "T_HEREDOC" ) {
				preg_match_all( '/[$]\w+/', $tokens[$i]['content'], $matches );
				$strVariables = array_merge_recursive( $strVariables, $matches );
			}
		}
		$strVariables = iterator_to_array(
			new RecursiveIteratorIterator( new RecursiveArrayIterator( $strVariables ) ),
			false
		);
		foreach ( $globalVariables as $global ) {
			$global = explode( '#', $global );
			if ( !in_array( $global[0], $otherVariables ) && !in_array( $global[0], $strVariables ) ) {
				$phpcsFile->addWarning( 'Global ' . $global[0] .' is never used.', $global[1] );
			}
		}
	}
}
