<?php

use OOUI\Widget;

/**
 * File <input> field.
 *
 * Besides the parameters recognized by HTMLFormField, the following are
 * recognized:
 *   accept - Acceptable MIME types to show in file choose
 *   multiple - Allow multiple files to be selected
 *
 * @stable to extend
 */
class HTMLFileField extends HTMLFormField {
	protected $mPlaceholder = '';
	protected $mAccept = '';

	/** @var bool */
	protected $mMultiple;

	/**
	 * @stable to call
	 *
	 * @param array $params
	 *   - placeholder/placeholder-message: set HTML placeholder attribute
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

		$this->mAccept = $params['accept'] ?: '';
		$this->mMultiple = !empty( $params['multiple'] );
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
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}
		if ( $this->mAccept !== '' ) {
			$attribs['accept'] = $this->mAccept;
		}
		if ( $this->mMultiple !== '' ) {
			$attribs['multiple'] = '';
		}

		$allowedParams = [
			'title',
			'tabindex',
			'disabled',
			'required',
			'autofocus',
			'readonly',
		];

		$attribs += $this->getAttributes( $allowedParams );

		return Html::input( $this->mName, $value, 'file', $attribs );
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
		if ( $this->mAccept !== '' ) {
			$attribs['accept'] = $this->mAccept;
		}
		if ( $this->mMultiple !== '' ) {
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

		$attribs += OOUI\Element::configFromHtmlAttributes(
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
		return new OOUI\SelectFileInputWidget( $params );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function shouldInfuseOOUI() {
		return true;
	}
}
