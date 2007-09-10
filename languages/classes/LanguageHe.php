<?php
/**
 * Hebrew (עברית)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Rotem Liss
 */

class LanguageHe extends Language {
	/**
	 * Convert grammar forms of words.
	 *
	 * Available cases:
	 * "prefixed" (or "תחילית") - when the word has a prefix
	 *
	 * @param string the word to convert
	 * @param string the case
	 */
	public function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['he'][$case][$word]) ) {
			return $wgGrammarForms['he'][$case][$word];
		}
		
		switch ( $case ) {
			case 'prefixed':
			case 'תחילית':
				# Duplicate the "Waw" if prefixed
				if ( substr( $word, 0, 2 ) == "ו" && substr( $word, 0, 4 ) != "וו" ) {
					$word = "ו".$word;
				}
				
				# Remove the "He" if prefixed
				if ( substr( $word, 0, 2 ) == "ה" ) {
					$word = substr( $word, 2 );
				}
				
				# Add a hyphen if non-Hebrew letters
				if ( substr( $word, 0, 2 ) < "א" || substr( $word, 0, 2 ) > "ת" ) {
					$word = "־".$word;
				}
		}
		
		return $word;
	}
	
	/**
	 * Gets a number and uses the suited form of the word.
	 *
	 * @param integer the number of items
	 * @param string the first form (singular)
	 * @param string the second form (plural)
	 * @param string the third form (2 items, plural is used if not applicable and not specified)
	 *
	 * @return string of the suited form of word
	 */
	public function convertPlural( $count, $w1, $w2, $w3) {
		if ( $count == '1' ) {
			return $w1;
		} elseif ( $count == '2' && $w3 ) {
			return $w3;
		} else {
			return $w2;
		}
	}
}

?>
