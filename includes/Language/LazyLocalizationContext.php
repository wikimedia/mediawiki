<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

/**
 * Wrapper for injecting a LocalizationContext with lazy initialization.
 *
 * @since 1.42
 * @ingroup Language
 */
class LazyLocalizationContext implements LocalizationContext {

	/** @var callable */
	private $instantiator;

	private ?LocalizationContext $context = null;

	public function __construct( callable $instantiator ) {
		$this->instantiator = $instantiator;
	}

	private function resolve(): LocalizationContext {
		if ( !$this->context ) {
			$this->context = ( $this->instantiator )();
		}

		return $this->context;
	}

	/** @inheritDoc */
	public function getLanguageCode() {
		return $this->resolve()->getLanguageCode();
	}

	/** @inheritDoc */
	public function msg( $key, ...$params ) {
		return $this->resolve()->msg( $key, ...$params );
	}
}
