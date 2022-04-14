<?php

namespace MediaWiki\User\TempUser;

use Language;
use MediaWiki\Languages\LanguageFactory;

/**
 * Serial mapping which uses a Language object to format serial numbers.
 *
 * @since 1.39
 */
class LocalizedNumericSerialMapping implements SerialMapping {
	/** @var Language */
	private $language;

	/**
	 * @param array $config
	 *   - language: The language code
	 * @param LanguageFactory $languageFactory
	 */
	public function __construct( $config, LanguageFactory $languageFactory ) {
		$this->language = $languageFactory->getLanguage( $config['language'] ?? 'en' );
	}

	public function getSerialIdForIndex( int $index ): string {
		return $this->language->formatNum( $index );
	}
}
