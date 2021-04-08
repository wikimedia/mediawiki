<?php

/**
 * Test converter (from Tajiki to latin orthography)
 */
class DummyConverter extends LanguageConverter {

	/**
	 * @var array
	 */
	private $table = [
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
	];

	/**
	 * Get Main language code.
	 *
	 * @return string
	 */
	public function getMainCode(): string {
		return 'tg';
	}

	/**
	 * Get supported variants of the language.
	 *
	 * @return array
	 */
	public function getLanguageVariants(): array {
		return [ 'tg', 'tg-latn', 'sgs', 'simple' ];
	}

	/**
	 * Get language variants fallbacks.
	 *
	 * @return array
	 */
	public function getVariantsFallbacks(): array {
		return [];
	}

	public function loadDefaultTables() {
		$this->mTables = [
			'sgs' => new ReplacementArray(),
			'simple' => new ReplacementArray(),
			'tg-latn' => new ReplacementArray( $this->table ),
			'tg' => new ReplacementArray()
		];
	}
}
