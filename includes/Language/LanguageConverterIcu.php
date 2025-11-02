<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\ReplacementArray;

/**
 * A class that extends LanguageConverterSpecific for converts that use
 * ICU rule-based transliterators.
 *
 * @ingroup Language
 */
abstract class LanguageConverterIcu extends LanguageConverterSpecific {

	/**
	 * @var Transliterator[]
	 */
	protected $mTransliterators;

	/**
	 * Creates empty tables. mTransliterators will be used instead.
	 */
	protected function loadDefaultTables(): array {
		$tables = [];
		foreach ( $this->getVariants() as $variant ) {
			$tables[$variant] = new ReplacementArray();
		}
		return $tables;
	}

	/** @inheritDoc */
	public function translate( $text, $variant ) {
		$text = parent::translate( $text, $variant );
		if ( trim( $text ) ) {
			$text = $this->icuTranslate( $text, $variant );
		}
		return $text;
	}

	/**
	 * Translate a string to a variant using ICU transliterator.
	 *
	 * @param string $text Text to convert
	 * @param string $variant Variant language code
	 * @return string Translated text
	 */
	public function icuTranslate( $text, $variant ) {
		return $this->getTransliterators()[$variant]->transliterate( $text );
	}

	/**
	 * Get the array mapping variants to ICU transliteration rules.
	 * Subclasses must implement this.
	 *
	 * @return string[]
	 */
	abstract protected function getIcuRules();

	/**
	 * Get the array mapping variants to ICU transliterators.
	 *
	 * @return Transliterator[]
	 */
	protected function getTransliterators() {
		if ( $this->mTransliterators === null ) {
			$this->mTransliterators = [];
			foreach ( $this->getIcuRules() as $variant => $rule ) {
				// @phan-suppress-next-line PhanTypeMismatchProperty Assume it's not null
				$this->mTransliterators[$variant] = Transliterator::createFromRules( $rule );
			}
			foreach ( $this->getTransliteratorAliases() as $alias => $variant ) {
				// @phan-suppress-next-line PhanTypeMismatchProperty Assume it's not null
				$this->mTransliterators[$alias] = $this->mTransliterators[$variant];
			}
		}
		return $this->mTransliterators;
	}

	/**
	 * Get the array mapping variant aliases to the main variant.
	 *
	 * @return string[]
	 */
	protected function getTransliteratorAliases() {
		return [];
	}
}
