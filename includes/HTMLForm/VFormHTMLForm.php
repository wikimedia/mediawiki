<?php

namespace MediaWiki\HTMLForm;

/**
 * HTML form generation and submission handling, vertical-form style.
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;

/**
 * Compact stacked vertical format for forms.
 *
 * @stable to extend
 * @deprecated since 1.45
 */
class VFormHTMLForm extends HTMLForm {
	/**
	 * Wrapper and its legend are never generated in VForm mode.
	 * @var bool
	 */
	protected $mWrapperLegend = false;

	/** @inheritDoc */
	protected $displayFormat = 'vform';

	/** @inheritDoc */
	public function __construct( $descriptor, IContextSource $context, $messagePrefix = '' ) {
		wfDeprecated( __CLASS__, '1.45' );
		parent::__construct( $descriptor, $context, $messagePrefix );
	}

	/** @inheritDoc */
	public static function loadInputFromParameters( $fieldname, $descriptor,
		?HTMLForm $parent = null
	) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	/** @inheritDoc */
	public function getHTML( $submitResult ) {
		$this->getOutput()->addModuleStyles( [
			'mediawiki.ui',
			'mediawiki.ui.button',
			'mediawiki.ui.input',
			'mediawiki.ui.checkbox',
		] );

		return parent::getHTML( $submitResult );
	}

	/**
	 * @inheritDoc
	 */
	protected function formatField( HTMLFormField $field, $value ) {
		return $field->getVForm( $value );
	}

	/** @inheritDoc */
	protected function getFormAttributes() {
		return [ 'class' => [ 'mw-htmlform', 'mw-ui-vform', 'mw-ui-container' ] ] +
			parent::getFormAttributes();
	}

	/** @inheritDoc */
	public function wrapForm( $html ) {
		// Always discard $this->mWrapperLegend
		return Html::rawElement( 'form', $this->getFormAttributes(), $html );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( VFormHTMLForm::class, 'VFormHTMLForm' );
