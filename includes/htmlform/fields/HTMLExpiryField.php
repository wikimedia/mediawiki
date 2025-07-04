<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Widget\ExpiryInputWidget;

/**
 * Expiry Field that allows the user to specify a precise date or a
 * relative date string.
 *
 * @stable to extend
 */
class HTMLExpiryField extends HTMLFormField {

	/**
	 * @var HTMLFormField
	 */
	protected $relativeField;

	/**
	 * Relative Date Time Field.
	 *
	 * @stable to call
	 * @param array $params
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		$type = !empty( $params['options'] ) ? 'selectorother' : 'text';
		$this->relativeField = $this->getFieldByType( $type );
	}

	/**
	 * @inheritDoc
	 *
	 * Use whatever the relative field is as the standard HTML input.
	 */
	public function getInputHTML( $value ) {
		return $this->relativeField->getInputHTML( $value );
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	protected function getOOUIModules() {
		return array_merge(
			[
				'mediawiki.widgets.expiry',
			],
			$this->relativeField->getOOUIModules()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getInputOOUI( $value ) {
		return new ExpiryInputWidget(
			$this->relativeField->getInputOOUI( $value ),
			[
				'id' => $this->mID,
				'required' => $this->mParams['required'] ?? false,
			]
		);
	}

	/** @inheritDoc */
	public function getInputCodex( $value, $hasErrors ) {
		return $this->relativeField->getInputCodex( $value, $hasErrors );
	}

	/**
	 * @inheritDoc
	 */
	public function loadDataFromRequest( $request ) {
		return $this->relativeField->loadDataFromRequest( $request );
	}

	/**
	 * Get the HTMLForm field by the type string.
	 *
	 * @param string $type
	 * @return HTMLFormField
	 */
	protected function getFieldByType( $type ) {
		$class = HTMLForm::$typeMappings[$type];
		$params = $this->mParams;
		$params['type'] = $type;
		$params['class'] = $class;

		// Remove Parameters that are being used on the parent.
		unset( $params['label-message'] );
		return new $class( $params );
	}

}

/** @deprecated class alias since 1.42 */
class_alias( HTMLExpiryField::class, 'HTMLExpiryField' );
