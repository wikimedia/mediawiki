<?php

class HTMLTextAreaField extends HTMLFormField {
	const DEFAULT_COLS = 80;
	const DEFAULT_ROWS = 25;

	protected $mPlaceholder = '';
	protected $mUseEditFont = false;

	/**
	 * @param array $params
	 *   - cols, rows: textarea size
	 *   - placeholder/placeholder-message: set HTML placeholder attribute
	 *   - spellcheck: set HTML spellcheck attribute
	 *   - useeditfont: add CSS classes to use the same font as the wikitext editor
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $params['placeholder-message'] ) ) {
			$this->mPlaceholder = $this->getMessage( $params['placeholder-message'] )->text();
		} elseif ( isset( $params['placeholder'] ) ) {
			$this->mPlaceholder = $params['placeholder'];
		}

		if ( isset( $params['useeditfont'] ) ) {
			$this->mUseEditFont = $params['useeditfont'];
		}
	}

	public function getCols() {
		return isset( $this->mParams['cols'] ) ? $this->mParams['cols'] : static::DEFAULT_COLS;
	}

	public function getRows() {
		return isset( $this->mParams['rows'] ) ? $this->mParams['rows'] : static::DEFAULT_ROWS;
	}

	public function getSpellCheck() {
		$val = isset( $this->mParams['spellcheck'] ) ? $this->mParams['spellcheck'] : null;
		if ( is_bool( $val ) ) {
			// "spellcheck" attribute literally requires "true" or "false" to work.
			return $val === true ? 'true' : 'false';
		}
		return null;
	}

	public function getInputHTML( $value ) {
		$classes = [];

		$attribs = [
				'id' => $this->mID,
				'cols' => $this->getCols(),
				'rows' => $this->getRows(),
				'spellcheck' => $this->getSpellCheck(),
			] + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			array_push( $classes, $this->mClass );
		}
		if ( $this->mUseEditFont ) {
			// The following classes can be used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			array_push(
				$classes,
				'mw-editfont-' . $this->mParent->getUser()->getOption( 'editfont' )
			);
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.editfont.styles' );
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}
		if ( count( $classes ) ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		$allowedParams = [
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus'
		];

		$attribs += $this->getAttributes( $allowedParams );
		return Html::textarea( $this->mName, $value, $attribs );
	}

	function getInputOOUI( $value ) {
		$classes = [];

		if ( isset( $this->mParams['cols'] ) ) {
			throw new Exception( "OOUIHTMLForm does not support the 'cols' parameter for textareas" );
		}

		$attribs = $this->getTooltipAndAccessKeyOOUI();

		if ( $this->mClass !== '' ) {
			array_push( $classes, $this->mClass );
		}
		if ( $this->mUseEditFont ) {
			// The following classes can be used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			array_push(
				$classes,
				'mw-editfont-' . $this->mParent->getUser()->getOption( 'editfont' )
			);
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.editfont.styles' );
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}
		if ( count( $classes ) ) {
			$attribs['classes'] = $classes;
		}

		$allowedParams = [
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus',
		];

		$attribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		return new OOUI\MultilineTextInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'value' => $value,
			'rows' => $this->getRows(),
		] + $attribs );
	}
}
