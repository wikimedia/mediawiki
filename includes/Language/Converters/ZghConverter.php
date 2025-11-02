<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\ReplacementArray;

/**
 * Standard Moroccan Amazigh specific code.
 *
 * Conversion script for Tifinagh to lowercase Latin for Standard Moroccan Amazigh.
 *
 *
 * Based on:
 *   - LanguageShi.php
 *   - https://fr.wikipedia.org/wiki/Tifinagh
 *
 * @ingroup Languages
 */
class ZghConverter extends LanguageConverterSpecific {
	/**
	 * The Tifinagh alphabet sequence is based on
	 * "Dictionnaire Général de la Langue Amazighe Informatisé"
	 * by IRCAM (https://tal.ircam.ma/dglai/lexieam.php, DGLAi),
	 * with the labio-velarization mark in the end.
	 */
	private const TO_LATIN = [
		'ⴰ' => 'a',
		'ⴱ' => 'b',
		'ⴳ' => 'g',
		'ⴷ' => 'd',
		'ⴹ' => 'ḍ',
		'ⴻ' => 'e',
		'ⴼ' => 'f',
		'ⴽ' => 'k',
		'ⵀ' => 'h',
		'ⵃ' => 'ḥ',
		'ⵄ' => 'ɛ',
		'ⵅ' => 'x',
		'ⵇ' => 'q',
		'ⵉ' => 'i',
		'ⵊ' => 'j',
		'ⵍ' => 'l',
		'ⵎ' => 'm',
		'ⵏ' => 'n',
		'ⵓ' => 'u',
		'ⵔ' => 'r',
		'ⵕ' => 'ṛ',
		'ⵖ' => 'ɣ',
		'ⵙ' => 's',
		'ⵚ' => 'ṣ',
		'ⵛ' => 'c',
		'ⵜ' => 't',
		'ⵟ' => 'ṭ',
		'ⵡ' => 'w',
		'ⵢ' => 'y',
		'ⵣ' => 'z',
		'ⵥ' => 'ẓ',
		'ⵯ' => 'ʷ',
	];

	public function getMainCode(): string {
		return 'zgh';
	}

	public function getLanguageVariants(): array {
		return [ 'zgh', 'zgh-latn' ];
	}

	public function getVariantsFallbacks(): array {
		return [];
	}

	protected function loadDefaultTables(): array {
		return [
			'zgh-latn' => new ReplacementArray( self::TO_LATIN ),
			'zgh' => new ReplacementArray()
		];
	}

	/** @inheritDoc */
	public function translate( $text, $toVariant ) {
		// We only convert zgh (zgh-Tfng) to zgh-Latn, not the
		// other way around. We also don't need to try to
		// convert if there is no text.
		if ( $toVariant === 'zgh' || !trim( $text ) ) {
			return $text;
		}

		$this->loadTables();
		$text = $this->mTables[$toVariant]->replace( $text );
		return $text;
	}
}
