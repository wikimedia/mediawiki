<?php

use MediaWiki\Widget\ExpiryWidget;

/**
 * Expiry Field that allows the user to specify a precise date or a
 * relative date string.
 */
class HTMLExpiryField extends HTMLFormField {

	/**
	 * @var HTMLFormField
	 */
	protected $relativeField;

	/**
	 * Relative Date Time Field.
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		$type = !empty( $params['options'] ) ? 'selectorother' : 'text';
		$this->relativeField = $this->getFieldByType( $type, 'relative' );
	}

	/**
	 * {@inheritdoc}
	 *
	 * Use whatever the relative field is as the stanrd HTML input.
	 */
	public function getInputHTML( $value ) {
		return $this->relativeField->getInputHtml( $value );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return array_merge(
			[
				'mediawiki.widgets.expiry',
			],
			$this->relativeField->getOOUIModules()
		);
	}

	public function getInputOOUI( $value ) {
		return new ExpiryWidget(
			$this->relativeField->getInputOOUI( $value ),
			[
				'id' => $this->mID
			]
		);
	}

	protected function getFieldByType( $type ) {
		$class = HTMLForm::$typeMappings[$type];
		$params = $this->mParams;
		$params['type'] = $type;
		$params['class'] = $class;

		// Remove Parameters that are being used on the parent.
		unset( $params['label-message'] );
		return new $class( $params );
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string
	 */
	public function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName );

			if ( $val === 'other' ) {
				return $request->getText( $this->mName . '-other' );
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}

}
