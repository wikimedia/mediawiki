<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Language select field.
 *
 * @stable to extend
 */
class HTMLSelectLanguageField extends HTMLSelectField {

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( $this->mParent instanceof HTMLForm ) {
			$config = $this->mParent->getConfig();
			$languageCode = $config->get( MainConfigNames::LanguageCode );
		} else {
			$languageCode = MediaWikiServices::getInstance()->getMainConfig()->get(
				MainConfigNames::LanguageCode );
		}

		$languages = MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageNames();

		// Make sure the site language is in the list;
		// a custom language code might not have a defined name…
		if ( !array_key_exists( $languageCode, $languages ) ) {
			$languages[$languageCode] = $languageCode;
		}

		ksort( $languages );

		foreach ( $languages as $code => $name ) {
			$this->mParams['options'][$code . ' - ' . $name] = $code;
		}

		$this->mParams['default'] ??= $languageCode;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSelectLanguageField::class, 'HTMLSelectLanguageField' );
