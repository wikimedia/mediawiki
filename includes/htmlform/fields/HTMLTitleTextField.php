<?php

use MediaWiki\Widget\TitleInputWidget;

/**
 * Implements a text input field for page titles.
 * Automatically does validation that the title is valid,
 * as well as autocompletion if using the OOUI display format.
 *
 * Note: Forms using GET requests will need to make sure the title value is not
 * an empty string.
 *
 * Optional parameters:
 * 'namespace' - Namespace the page must be in
 * 'relative' - If true and 'namespace' given, strip/add the namespace from/to the title as needed
 * 'creatable' - Whether to validate the title is creatable (not a special page)
 * 'exists' - Whether to validate that the title already exists
 *
 * @since 1.26
 */
class HTMLTitleTextField extends HTMLTextField {
	public function __construct( $params ) {
		$params += [
			'namespace' => false,
			'relative' => false,
			'creatable' => false,
			'exists' => false,
		];

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		if ( $this->mParent->getMethod() === 'get' && $value === '' ) {
			// If the form is a GET form and has no value, assume it hasn't been
			// submitted yet, and skip validation
			return parent::validate( $value, $alldata );
		}
		try {
			if ( !$this->mParams['relative'] ) {
				$title = Title::newFromTextThrow( $value );
			} else {
				// Can't use Title::makeTitleSafe(), because it doesn't throw useful exceptions
				global $wgContLang;
				$namespaceName = $wgContLang->getNsText( $this->mParams['namespace'] );
				$title = Title::newFromTextThrow( $namespaceName . ':' . $value );
			}
		} catch ( MalformedTitleException $e ) {
			$msg = $this->msg( $e->getErrorMessage() );
			$params = $e->getErrorMessageParameters();
			if ( $params ) {
				$msg->params( $params );
			}
			return $msg;
		}

		$text = $title->getPrefixedText();
		if ( $this->mParams['namespace'] !== false &&
			!$title->inNamespace( $this->mParams['namespace'] )
		) {
			return $this->msg( 'htmlform-title-badnamespace', $this->mParams['namespace'], $text );
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
