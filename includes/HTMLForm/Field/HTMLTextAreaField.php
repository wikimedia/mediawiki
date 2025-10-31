<?php

namespace MediaWiki\HTMLForm\Field;

use InvalidArgumentException;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\MediaWikiServices;

/**
 * @stable to extend
 */
class HTMLTextAreaField extends HTMLFormField {
	protected const DEFAULT_COLS = 80;
	protected const DEFAULT_ROWS = 25;

	/** @var string */
	protected $mPlaceholder = '';
	/** @var bool */
	protected $mUseEditFont = false;

	/**
	 * @stable to call
	 *
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

	/**
	 * @return int
	 */
	public function getCols() {
		return $this->mParams['cols'] ?? static::DEFAULT_COLS;
	}

	/**
	 * @return int
	 */
	public function getRows() {
		return $this->mParams['rows'] ?? static::DEFAULT_ROWS;
	}

	/**
	 * @return string|null
	 */
	public function getSpellCheck() {
		$val = $this->mParams['spellcheck'] ?? null;
		if ( is_bool( $val ) ) {
			// "spellcheck" attribute literally requires "true" or "false" to work.
			return $val ? 'true' : 'false';
		}
		return null;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputHTML( $value ) {
		$classes = [];

		$attribs = [
				'id' => $this->mID,
				'cols' => $this->getCols(),
				'rows' => $this->getRows(),
				'spellcheck' => $this->getSpellCheck(),
			] + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$classes[] = $this->mClass;
		}
		if ( $this->mUseEditFont ) {
			$userOptionsLookup = MediaWikiServices::getInstance()
				->getUserOptionsLookup();
			// The following classes can be used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			$classes[] =
				'mw-editfont-' .
				$userOptionsLookup->getOption( $this->mParent->getUser(), 'editfont' );
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.editfont.styles' );
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}
		if ( $classes ) {
			$attribs['class'] = $classes;
		}

		$allowedParams = [
			'maxlength',
			'minlength',
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus'
		];

		$attribs += $this->getAttributes( $allowedParams );
		return Html::textarea( $this->mName, $value, $attribs );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputOOUI( $value ) {
		$classes = [];

		if ( isset( $this->mParams['cols'] ) ) {
			throw new InvalidArgumentException( "OOUIHTMLForm does not support the 'cols' parameter for textareas" );
		}

		$attribs = $this->getTooltipAndAccessKeyOOUI();

		if ( $this->mClass !== '' ) {
			$classes[] = $this->mClass;
		}
		if ( $this->mUseEditFont ) {
			$userOptionsLookup = MediaWikiServices::getInstance()
				->getUserOptionsLookup();
			// The following classes can be used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			$classes[] =
				'mw-editfont-' .
				$userOptionsLookup->getOption( $this->mParent->getUser(), 'editfont' );
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.editfont.styles' );
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}
		if ( count( $classes ) ) {
			$attribs['classes'] = $classes;
		}

		$allowedParams = [
			'maxlength',
			'minlength',
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus',
		];

		$attribs += \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		return new \OOUI\MultilineTextInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'value' => $value,
			'rows' => $this->getRows(),
		] + $attribs );
	}

	/** @inheritDoc */
	public function getInputCodex( $value, $hasErrors ) {
		$textareaClasses = [ 'cdx-text-area__textarea' ];
		if ( $this->mClass !== '' ) {
			$textareaClasses[] = $this->mClass;
		}
		if ( $this->mUseEditFont ) {
			$userOptionsLookup = MediaWikiServices::getInstance()
				->getUserOptionsLookup();
			// The following classes can be used here:
			// * mw-editfont-monospace
			// * mw-editfont-sans-serif
			// * mw-editfont-serif
			$textareaClasses[] =
				'mw-editfont-' .
				$userOptionsLookup->getOption( $this->mParent->getUser(), 'editfont' );
			$this->mParent->getOutput()->addModuleStyles( 'mediawiki.editfont.styles' );
		}

		$textareaAttribs = [
			'id' => $this->mID,
			'cols' => $this->getCols(),
			'rows' => $this->getRows(),
			'spellcheck' => $this->getSpellCheck(),
			'class' => $textareaClasses
		] + $this->getTooltipAndAccessKey();

		if ( $this->mPlaceholder !== '' ) {
			$textareaAttribs['placeholder'] = $this->mPlaceholder;
		}

		$allowedParams = [
			'maxlength',
			'minlength',
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus'
		];
		$textareaAttribs += $this->getAttributes( $allowedParams );

		$textarea = Html::textarea( $this->mName, $value, $textareaAttribs );

		$wrapperAttribs = [ 'class' => [ 'cdx-text-area' ] ];
		if ( $hasErrors ) {
			$wrapperAttribs['class'][] = 'cdx-text-area--status-error';
		}
		return Html::rawElement(
			'div',
			$wrapperAttribs,
			$textarea
		);
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLTextAreaField::class, 'HTMLTextAreaField' );
