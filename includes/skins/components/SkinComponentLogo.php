<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

namespace MediaWiki\Skin;

use Config;
use Language;
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

	/**
	 * @param Config $config
	 * @param Language $language
	 */
	public function __construct( Config $config, Language $language ) {
		$this->config = $config;
		$this->language = $language->getCode();
	}

	/**
	 * @return string|null
	 */
	private function getLanguage(): ?string {
		return $this->language;
	}

	/**
	 * @return Config
	 */
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
