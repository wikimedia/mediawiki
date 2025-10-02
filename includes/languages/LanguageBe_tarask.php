<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license GPL-2.0-or-later
 * @license GFDL-1.3-or-later
 */

use MediaWiki\Language\Language;

/**
 * Belarusian in Taraškievica orthography (Беларуская тарашкевіца)
 *
 * @ingroup Languages
 * @see https://be-tarask.wikipedia.org/wiki/Project_talk:LanguageBe_tarask.php
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class LanguageBe_tarask extends Language {
	/**
	 * The Belarusian language uses apostrophe sign,
	 * but the characters used for this could be both U+0027 and U+2019.
	 * This function unifies apostrophe sign in search index values
	 * to enable search using both apostrophe signs.
	 *
	 * @inheritDoc
	 */
	public function normalizeForSearch( $text ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex

		# Replacing apostrophe sign U+2019 with U+0027
		$text = str_replace( "\u{2019}", '\'', $text );

		return parent::normalizeForSearch( $text );
	}
}
