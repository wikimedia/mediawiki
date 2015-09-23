<?php
/**
 * Check to see if there's any character before php open tag <? or <?php
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_ExtraCharacters_CharacterBeforePHPOpeningTagSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return array( T_OPEN_TAG );
	}
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		// do nothing if opening tag is the first character
		if ( $stackPtr == 0 ) {
			return;
		}
		$tokens = $phpcsFile->getTokens();
		$isNotFirstOpenTag = $phpcsFile->findPrevious(
			array( T_OPEN_TAG ),
			( $stackPtr - 1 ),
			null,
			false
		);
		// some other character beginning file
		if ( $isNotFirstOpenTag === false ) {
			$validShebang = false;
			// a shebang is allowed on first line only if
			// it is followed by a php open tag on next line
			if ( $stackPtr == 1 && $tokens[1]['line'] == 2 ) {
				// the php tag is the second token and it is on second line
				// so the first token is on the first line

				// check if it is valid shebang
				// T_HASHBANG is a token used in HHVM >=3.5, <3.7 (T103119)
				if ( in_array( $tokens[0]['type'], array( 'T_INLINE_HTML', 'T_HASHBANG' ) )
					&& substr( $tokens[0]['content'], 0, 2 ) == '#!' ) {
					$validShebang = true;
				}
			}
			if ( !$validShebang ) {
				$error = 'Extra character found before first <?';
				$phpcsFile->addError( $error, $stackPtr, 'Found' );
			}
		}
	}
}
