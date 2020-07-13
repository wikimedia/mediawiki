<?php

use MediaWiki\Widget\TitleInputWidget;

/**
 * Implements a text input field for page titles.
 * Automatically does validation that the title is valid,
 * as well as autocompletion if using the OOUI display format.
 *
 * Optional parameters:
 * 'namespace' - Namespace the page must be in
 * 'relative' - If true and 'namespace' given, strip/add the namespace from/to the title as needed
 * 'creatable' - Whether to validate the title is creatable (not a special page)
 * 'exists' - Whether to validate that the title already exists
 *
 * @stable to extend
 * @since 1.26
 */
class HTMLTitleTextField extends HTMLTextField {
	/*
	 * @stable to call
	 */
	public function __construct( $params ) {
		$params += [
			'namespace' => false,
			'relative' => false,
			'creatable' => false,
			'exists' => false,
			// This overrides the default from HTMLFormField
			'required' => true,
		];

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		// Default value (from getDefault()) is null, which breaks Title::newFromTextThrow() below
		if ( $value === null ) {
			$value = '';
		}

		if ( !$this->mParams['required'] && $value === '' ) {
			// If this field is not required and the value is empty, that's okay, skip validation
			return parent::validate( $value, $alldata );
		}

		try {
			if ( !$this->mParams['relative'] ) {
				$title = Title::newFromTextThrow( $value );
			} else {
				// Can't use Title::makeTitleSafe(), because it doesn't throw useful exceptions
				$title = Title::newFromTextThrow( Title::makeName( $this->mParams['namespace'], $value ) );
			}
		} catch ( MalformedTitleException $e ) {
			return $this->msg( $e->getErrorMessage(), $e->getErrorMessageParameters() );
		}

		$text = $title->getPrefixedText();
		if ( $this->mParams['namespace'] !== false &&
			!$title->inNamespace( $this->mParams['namespace'] )
		) {
			return $this->msg( 'htmlform-title-badnamespace', $text, $this->mParams['namespace'] );
		}

		if ( $this->mParams['creatable'] && !$title->canExist() ) {
			return $this->msg( 'htmlform-title-not-creatable', $text );
		}

		if ( $this->mParams['exists'] && !$title->exists() ) {
			return $this->msg( 'htmlform-title-not-exists', $text );
		}

		return parent::validate( $value, $alldata );
	}

	protected function getInputWidget( $params ) {
		if ( $this->mParams['namespace'] !== false ) {
			$params['namespace'] = $this->mParams['namespace'];
		}
		$params['relative'] = $this->mParams['relative'];
		return new TitleInputWidget( $params );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		// FIXME: TitleInputWidget should be in its own module
		return [ 'mediawiki.widgets' ];
	}

	public function getInputHtml( $value ) {
		// add mw-searchInput class to enable search suggestions for non-OOUI, too
		$this->mClass .= 'mw-searchInput';

		// return the HTMLTextField html
		return parent::getInputHTML( $value );
	}

	protected function getDataAttribs() {
		return [
			'data-mw-searchsuggest' => FormatJson::encode( [
				'wrapAsLink' => false,
			] ),
		];
	}
}
