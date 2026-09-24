<?php
declare( strict_types = 1 );

namespace MediaWiki\Site;

use MediaWiki\Language\LanguageNameUtils;

/**
 * Prevent invalid Site attributes
 *
 * @license GPL-2.0-or-later
 */
class SiteSanitizer {
	public function __construct(
		private LanguageNameUtils $languageNameUtils,
		private array $siteTypes
	) {
	}

	public function newSiteForType( string $typeKey ): Site {
		if ( array_key_exists( $typeKey, $this->siteTypes ) ) {
			return new $this->siteTypes[$typeKey]();
		}

		return new Site();
	}

	public function sanitizeSite( Site $site ) {
		$lang = $site->getLanguageCode();
		if ( $lang !== null && !$this->languageNameUtils->isValidCode( $lang ) ) {
			$site->setLanguageCode( null );
		}
	}
}
