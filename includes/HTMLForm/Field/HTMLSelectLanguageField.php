<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Widget\LanguageSelectWidget;

/**
 * Language select field.
 *
 * @stable to extend
 */
class HTMLSelectLanguageField extends HTMLSelectField {
	private bool $useCodex = false;
	/** @var array<string, string> Language code => language name */
	private array $languages = [];

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$this->useCodex = $params['useCodex'] ?? false;
		parent::__construct( $params );

		if ( $this->mParent instanceof HTMLForm ) {
			$config = $this->mParent->getConfig();
			$languageCode = $config->get( MainConfigNames::LanguageCode );
		} else {
			$languageCode = MediaWikiServices::getInstance()->getMainConfig()->get(
				MainConfigNames::LanguageCode );
		}

		// Use provided languages array if given, otherwise fetch all languages
		if ( isset( $params['languages'] ) && is_array( $params['languages'] ) ) {
			$this->languages = $params['languages'];
		} else {
			$this->languages = MediaWikiServices::getInstance()
				->getLanguageNameUtils()
				->getLanguageNames();
		}

		// Make sure the site language is in the list;
		// a custom language code might not have a defined name…
		if ( !array_key_exists( $languageCode, $this->languages ) ) {
			$this->languages[$languageCode] = $languageCode;
		}

		ksort( $this->languages );

		foreach ( $this->languages as $code => $name ) {
			$this->mParams['options'][$code . ' - ' . $name] = $code;
		}

		$this->mParams['default'] ??= $languageCode;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputOOUI( $value ) {
		if ( $this->useCodex ) {
			return $this->getInputCodex( $value, false );
		}
		return parent::getInputOOUI( $value );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputCodex( $value, $hasErrors ) {
		// Add module for language selector widget
		$this->mParent->getOutput()->addModules( 'mediawiki.widgets.LanguageSelectWidget' );

		$standardAttribs = $this->getAttributes( [ 'disabled', 'required', 'multiple' ] );

		$widget = new LanguageSelectWidget( [
			'languages' => $this->languages,
			'name' => $this->mName,
			'value' => $value !== null && $value !== '' ? $value : ( $this->mParams['default'] ?? null ),
			'id' => $this->mID,
			'cssclass' => trim( $this->mClass . ' cdx-text-input__input' ),
		] + $standardAttribs );

		return $widget->toString();
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSelectLanguageField::class, 'HTMLSelectLanguageField' );
