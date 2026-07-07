<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use Wikimedia\Bcp47Code\Bcp47Code;

/**
 * An implementation of {@link LocalizationContext} that implements only the methods defined in the interface.
 *
 * Intended for use where you don't have access to a full {@link IContextSource} such as when you only have
 * access to a ResourceLoader {@link \MediaWiki\ResourceLoader\Context} (which does not implement
 * {@link LocalizationContext}) but need to provide a {@link LocalizationContext}.
 *
 * Callers should usually ensure the languages are the same for the {@link Bcp47Code} and
 * the {@link MessageLocalizer}.
 *
 * @newable
 * @since 1.47
 * @ingroup Language
 */
class SimpleLocalizationContext implements LocalizationContext {

	public function __construct(
		private readonly MessageLocalizer $messageLocalizer,
		private readonly Bcp47Code $language,
	) {
	}

	/** @inheritDoc */
	public function getLanguageCode() {
		return $this->language;
	}

	/** @inheritDoc */
	public function msg( $key, ...$params ) {
		return $this->messageLocalizer->msg( $key, ...$params );
	}
}
