<?php

namespace MediaWiki\Tests\Mocks\Language;

use MediaWiki\Language\LanguageConverter;
use Wikimedia\ReplacementArray;

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
	 */
	public function getMainCode(): string {
		return 'tg';
	}

	/**
	 * Get supported variants of the language.
	 */
	public function getLanguageVariants(): array {
		return [ 'tg', 'tg-latn', 'sgs', 'simple' ];
	}

	/**
	 * Get language variants fallbacks.
	 */
	public function getVariantsFallbacks(): array {
		return [];
	}

	public function loadDefaultTables(): array {
		return [
			'sgs' => new ReplacementArray(),
			'simple' => new ReplacementArray(),
			'tg-latn' => new ReplacementArray( $this->table ),
			'tg' => new ReplacementArray()
		];
	}
}
