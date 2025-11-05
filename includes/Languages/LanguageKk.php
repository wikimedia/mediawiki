<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Kazakh (Қазақша) specific code.
 *
 * This handles Cyrillic, Latin and Arabic scripts for Kazakh.
 * Right now, we distinguish `kk_cyrl`, `kk_latn`, `kk_arab`, `kk_kz`, `kk_tr`,
 * and `kk_cn`.
 *
 * @ingroup Languages
 */
class LanguageKk extends LanguageKk_cyrl {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		// T277689: If there's no word, then there's nothing to convert.
		if ( $word === '' ) {
			return '';
		}
		return parent::convertGrammarKk_cyrl( $word, $case );
	}
}
