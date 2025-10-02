<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Skin;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\ResourceLoader as RL;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentLogo implements SkinComponent {
	/** @var Config */
	private $config;
	/** @var string|null */
	private $language;

	public function __construct( Config $config, Language $language ) {
		$this->config = $config;
		$this->language = $language->getCode();
	}

	private function getLanguage(): ?string {
		return $this->language;
	}

	private function getConfig(): Config {
		return $this->config;
	}

	/**
	 * @inheritDoc
	 * Since 1.35 (all fields optional):
	 * - string 1x Path to a square icon at 1x resolution
	 * - string 2x Path to a square icon at 2x resolution
	 * - string icon Path to a square icon
	 * - array wordmark with `src`, `width`, `height` and `style` keys.
	 * - array tagline with `src`, `width`, `height` and `style` keys.
	 */
	public function getTemplateData(): array {
		return RL\SkinModule::getAvailableLogos( $this->getConfig(), $this->getLanguage() );
	}
}
