<?php

use MediaWiki\Widget\TitleInputWidget;

/**
 * Implements a text input field for page titles.
 * Automatically does validation that the title is valid,
 * as well as autocompletion if using the OOUI display format.
 *
 * FIXME: Does not work for forms that support GET requests.
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
		$params += array(
			'namespace' => false,
			'relative' => false,
			'creatable' => false,
			'exists' => false,
		);

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
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
			return $msg->parse();
		}

		$text = $title->getPrefixedText();
		if ( $this->mParams['namespace'] !== false && !$title->inNamespace( $this->mParams['namespace'] ) ) {
			return $this->msg( 'htmlform-title-badnamespace', $this->mParams['namespace'], $text )->parse();
		}

		if ( $this->mParams['creatable'] && !$title->canExist() ) {
			return $this->msg( 'htmlform-title-not-creatable', $text )->escaped();
		}

		if ( $this->mParams['exists'] && !$title->exists() ) {
			return $this->msg( 'htmlform-title-not-exists', $text )->parse();
		}

		return parent::validate( $value, $alldata );
	}

	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModules( 'mediawiki.widgets' );
		if ( $this->mParams['namespace'] !== false ) {
			$params['namespace'] = $this->mParams['namespace'];
		}
		$params['relative'] = $this->mParams['relative'];
		return new TitleInputWidget( $params );
	}
}
