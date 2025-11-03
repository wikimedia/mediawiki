<?php

namespace MediaWiki\Skin;

use MediaWiki\Language\Language;
use MessageLocalizer;

class SkinComponentRenderedWith implements SkinComponent {
	private Language $language;
	private MessageLocalizer $localizer;

	/**
	 * @param SkinComponentRegistryContext $skinContext
	 * @param bool $useParsoid whether Parsoid was used to render this page
	 */
	public function __construct(
		SkinComponentRegistryContext $skinContext,
		private bool $useParsoid = false,
	) {
		$this->localizer = $skinContext->getMessageLocalizer();
		$this->language = $skinContext->getLanguage();
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$localizer = $this->localizer;
		$language = $this->language;
		$useParsoid = $this->useParsoid;

		$msg = $useParsoid ?
			 $localizer->msg( 'renderedwith-parsoid' ) :
			 $localizer->msg( 'renderedwith-legacy' );

		return [
			'is-parsoid' => $useParsoid,
			'text' => $msg->isDisabled() ? '' : $msg->parse(),
		];
	}
}
