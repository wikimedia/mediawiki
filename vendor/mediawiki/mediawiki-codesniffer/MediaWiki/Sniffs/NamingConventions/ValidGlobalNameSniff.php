<?php
/**
 * Verify MediaWiki global variable naming convention.
 * A global name must be prefixed with 'wg'.
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_NamingConventions_ValidGlobalNameSniff implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	/**
	 * http://php.net/manual/en/reserved.variables.argv.php
	 */
	private static $PHPReserved = array(
		'$GLOBALS',
		'$_SERVER',
		'$_GET',
		'$_POST',
		'$_FILES',
		'$_REQUEST',
		'$_SESSION',
		'$_ENV',
		'$_COOKIE',
		'$php_errormsg',
		'$HTTP_RAW_POST_DATA',
		'$http_response_header',
		'$argc',
		'$argv'
	);

	private static $mediaWikiValid = array(
		'$messageMemc',
		'$parserMemc',
		'$IP',
	);

	public function register() {
		return array( T_GLOBAL );
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$nameIndex  = $phpcsFile->findNext( T_VARIABLE, $stackPtr + 1 );
		$semicolonIndex  = $phpcsFile->findNext( T_SEMICOLON, $stackPtr + 1 );

		while ( $nameIndex < $semicolonIndex ) {

			if ( $tokens[ $nameIndex ][ 'code' ] !== T_WHITESPACE
					&& $tokens[ $nameIndex ][ 'code' ] !== T_COMMA ) {

				$globalName = $tokens[$nameIndex]['content'];

				if ( in_array( $globalName, self::$mediaWikiValid ) ||
					in_array( $globalName, self::$PHPReserved )
				) {
					return;
				}

				// Skip '$' and forge a valid global variable name
				$expected = '$wg' . ucfirst( substr( $globalName, 1 ) );

				// Verify global is prefixed with wg
				if ( strpos( $globalName, '$wg' ) !== 0 ) {
					$phpcsFile->addError(
						'Global variable "%s" is lacking \'wg\' prefix. Should be "%s".',
						$stackPtr,
						'wgPrefix',
						array( $globalName, $expected )
					);
				} else {
					// Verify global is probably CamelCase
					$val = ord( substr( $globalName, 3, 1 ) );
					if ( !( $val >= 65 && $val <= 90 ) ) {
						$phpcsFile->addError(
							'Global variable "%s" should use CamelCase: "%s"',
							$stackPtr,
							'CamelCase',
							array( $globalName, $expected )
						);
					}
				}
			}
			$nameIndex++;
		}
	}
}
