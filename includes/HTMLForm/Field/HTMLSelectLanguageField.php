<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Json\FormatJson;
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
	private ?HTMLMultiSelectField $multiSelectProxy = null;

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
	public function getOOUI( $value ) {
		$layout = parent::getOOUI( $value );

		// When using Codex, the field returns raw HTML, which evaluates $infusable to false.
		// So data-ooui config won't be serialized, so we must manually set data-cond-state
		// for cond-state.js to use in its non-OOUI fallback handling.
		if ( $this->useCodex && $this->mCondState ) {
			$layout->setAttributes( [
				'data-cond-state' => FormatJson::encode( $this->parseCondStateForClient() )
			] );
		}

		return $layout;
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

		$standardAttribs = $this->getAttributes( [ 'disabled', 'required', 'multiple', 'size', 'placeholder' ] );
		$isMultiple = $this->mParams['multiple'] ?? false;
		$defaultValue = $isMultiple ? [] : null;

		$widget = new LanguageSelectWidget( [
			'languages' => $this->languages,
			'name' => $this->mName,
			'value' => $value ?: ( $this->mParams['default'] ?? $defaultValue ),
			'id' => $this->mID,
			'cssclass' => trim( $this->mClass . ' cdx-text-input__input' ),
		] + $standardAttribs );

		return $widget->toString();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function loadDataFromRequest( $request ) {
		if ( $this->isCodexAndMulti() ) {
			return $this->getMultiSelectProxy()->loadDataFromRequest( $request );
		}

		return parent::loadDataFromRequest( $request );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function validate( $value, $alldata ) {
		if ( $this->isCodexAndMulti() ) {
			return $this->getMultiSelectProxy()->validate( $value, $alldata );
		}

		return parent::validate( $value, $alldata );
	}

	private function isCodexAndMulti(): bool {
		return $this->useCodex && ( $this->mParams['multiple'] ?? false );
	}

	private function getMultiSelectProxy(): HTMLMultiSelectField {
		if ( $this->multiSelectProxy === null ) {
			$name = $this->mParams['fieldname'];

			// Since the HTMLSelectLanguageField didn't traditionally know how to handle multi-select,
			// we hack it here by delegating to HTMLMultiSelectField, it natively does not include the []
			// in the fieldname, and HTMLRequest::getArray() doesn't support the [] syntax, so we need to remove it
			if ( str_ends_with( $name, '[]' ) ) {
				$name = substr( $name, 0, -2 );
			}
			$this->multiSelectProxy = new HTMLMultiSelectField( [
				'fieldname' => $name,
				'type' => 'multiselect',
				'parent' => $this->mParent,
				'options' => $this->mParams['options'] ?? [],
				'required' => $this->mParams['required'] ?? false,
				'default' => $this->getDefault()
			] );
		}

		return $this->multiSelectProxy;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSelectLanguageField::class, 'HTMLSelectLanguageField' );
