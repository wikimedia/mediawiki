<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;
use OOUI\Widget;

/**
 * File <input> field.
 *
 * Besides the parameters recognized by HTMLFormField, the following are
 * recognized:
 *   placeholder/placeholder-message - HTML placeholder attribute
 *   accept - Array of acceptable MIME types/extensions to show in file chooser,
 *            null to accept all files.
 *   multiple - Allow multiple files to be selected
 *
 * @stable to extend
 */
class HTMLFileField extends HTMLFormField {
	protected $mPlaceholder = '';
	protected $mAccept = null;

	/** @var bool */
	protected $mMultiple;

	/**
	 * @stable to call
	 *
	 * @param array $params
	 *   - placeholder/placeholder-message
	 *   - accept
	 *   - multiple
	 */
	public function __construct( $params ) {
		if ( isset( $params['autocomplete'] ) && is_bool( $params['autocomplete'] ) ) {
			$params['autocomplete'] = $params['autocomplete'] ? 'on' : 'off';
		}

		parent::__construct( $params );

		if ( isset( $params['placeholder-message'] ) ) {
			$this->mPlaceholder = $this->getMessage( $params['placeholder-message'] )->text();
		} elseif ( isset( $params['placeholder'] ) ) {
			$this->mPlaceholder = $params['placeholder'];
		}

		$this->mAccept = $params['accept'] ?? null;
		$this->mMultiple = !empty( $params['multiple'] );
	}

	/**
	 * @inheritDoc
	 */
	public function loadDataFromRequest( $request ) {
		return $request->getUpload( $this->mName )->getName();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputHTML( $value ) {
		$attribs = [
			'id' => $this->mID,
			'name' => $this->mName,
			'dir' => $this->mDir,
		] + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}
		if ( $this->mAccept ) {
			$attribs['accept'] = implode( ',', $this->mAccept );
		}
		if ( $this->mMultiple ) {
			$attribs['multiple'] = '';
		}
		// Note: Placeholders are not supported by native file inputs

		$allowedParams = [
			'title',
			'tabindex',
			'disabled',
			'required',
			'autofocus',
			'readonly',
		];

		$attribs += $this->getAttributes( $allowedParams );

		return Html::input( $this->mName, $value ?? '', 'file', $attribs );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputOOUI( $value ) {
		$attribs = $this->getTooltipAndAccessKeyOOUI();

		if ( $this->mClass !== '' ) {
			$attribs['classes'] = [ $this->mClass ];
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}
		if ( $this->mAccept ) {
			$attribs['accept'] = $this->mAccept;
		}
		if ( $this->mMultiple ) {
			$attribs['multiple'] = true;
		}

		# @todo Enforce pattern, step, required, readonly on the server side as
		# well
		$allowedParams = [
			'title',
			'tabindex',
			'disabled',
			'required',
			'autofocus',
			'readonly',
		];

		$attribs += \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		return $this->getInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'dir' => $this->mDir,
		] + $attribs );
	}

	/**
	 * @stable to override
	 *
	 * @param array $params
	 *
	 * @return Widget
	 */
	protected function getInputWidget( $params ) {
		return new \OOUI\SelectFileInputWidget( $params );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLFileField::class, 'HTMLFileField' );
