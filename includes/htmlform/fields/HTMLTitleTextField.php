<?php

namespace MediaWiki\HTMLForm\Field;

use InvalidArgumentException;
use MediaWiki\Json\FormatJson;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Widget\TitleInputWidget;

/**
 * Implements a text input field for page titles.
 * Automatically does validation that the title is valid,
 * as well as autocompletion if using the OOUI display format.
 *
 * Optional parameters:
 * 'namespace' - Namespace the page must be in (use namespace constant; one of the NS_* constants may be used)
 * 'relative' - If true and 'namespace' given, strip/add the namespace from/to the title as needed
 * 'creatable' - Whether to validate the title is creatable (not a special page)
 * 'exists' - Whether to validate that the title already exists
 * 'interwiki' – Tolerate interwiki links (other conditions such as 'namespace' or 'exists' will be
 *   ignored if the title is an interwiki title). Cannot be used together with 'relative'.
 *
 * @stable to extend
 * @since 1.26
 */
class HTMLTitleTextField extends HTMLTextField {
	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$params += [
			'namespace' => false,
			'relative' => false,
			'creatable' => false,
			'exists' => false,
			'interwiki' => false,
			// This overrides the default from HTMLFormField
			'required' => true,
		];

		parent::__construct( $params );
	}

	/** @inheritDoc */
	public function validate( $value, $alldata ) {
		if ( $this->mParams['interwiki'] && $this->mParams['relative'] ) {
			// relative and interwiki cannot be used together, because we don't have a way to know about
			// namespaces used by the other wiki (and it might actually be a non-wiki link, too).
			throw new InvalidArgumentException( 'relative and interwiki may not be used together' );
		}
		// Default value (from getDefault()) is null, which breaks Title::newFromTextThrow() below
		$value ??= '';

		if ( !$this->mParams['required'] && $value === '' ) {
			// If this field is not required and the value is empty, that's okay, skip validation
			return parent::validate( $value, $alldata );
		}

		$titleFactory = MediaWikiServices::getInstance()->getTitleFactory();
		try {
			if ( !$this->mParams['relative'] ) {
				$title = $titleFactory->newFromTextThrow( $value );
			} else {
				// Can't use makeTitleSafe(), because it doesn't throw useful exceptions
				$title = $titleFactory->newFromTextThrow( Title::makeName( $this->mParams['namespace'], $value ) );
			}
		} catch ( MalformedTitleException $e ) {
			return $this->msg( $e->getErrorMessage(), $e->getErrorMessageParameters() );
		}

		if ( $title->isExternal() ) {
			if ( $this->mParams['interwiki'] ) {
				// We cannot validate external titles, skip the rest of the validation
				return parent::validate( $value, $alldata );
			} else {
				return $this->msg( 'htmlform-title-interwiki', $title->getPrefixedText() );
			}
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

	/** @inheritDoc */
	protected function getInputWidget( $params ) {
		if ( $this->mParams['namespace'] !== false ) {
			$params['namespace'] = $this->mParams['namespace'];
		}
		$params['relative'] = $this->mParams['relative'];
		return new TitleInputWidget( $params );
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}

	/** @inheritDoc */
	protected function getOOUIModules() {
		// FIXME: TitleInputWidget should be in its own module
		return [ 'mediawiki.widgets' ];
	}

	/** @inheritDoc */
	public function getInputHtml( $value ) {
		// add mw-searchInput class to enable search suggestions for non-OOUI, too
		$this->mClass .= 'mw-searchInput';

		// return the HTMLTextField html
		return parent::getInputHTML( $value );
	}

	/** @inheritDoc */
	protected function getDataAttribs() {
		return [
			'data-mw-searchsuggest' => FormatJson::encode( [
				'wrapAsLink' => false,
			] ),
		];
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLTitleTextField::class, 'HTMLTitleTextField' );
