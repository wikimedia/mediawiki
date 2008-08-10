<?php

/** Hungarian localisation for MediaWiki
 *
 * @ingroup Language
 */
class LanguageHu extends Language {
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms[$this->getCode()][$case][$word]) ) {
			return $wgGrammarForms[$this->getCode()][$case][$word];
		}

		switch ( $case ) {
			case 'rol':
				return $word . 'ról';
			case 'ba':
				return $word . 'ba';
			case 'k':
				return $word . 'k';
		}
	}
}
