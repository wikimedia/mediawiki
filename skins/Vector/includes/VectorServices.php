<?php

namespace MediaWiki\Skins\Vector;

use MediaWiki\MediaWikiServices;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MediaWiki\Skins\Vector\Services\LanguageService;

/**
 * A service locator for services specific to Vector.
 *
 * @package Vector
 * @internal
 */
final class VectorServices {

	/**
	 * Gets the feature manager service.
	 *
	 * Per its definition in ServiceWiring.php, the feature manager service is bound to the global
	 * request and user objects.
	 *
	 * @return FeatureManager
	 */
	public static function getFeatureManager(): FeatureManager {
		return MediaWikiServices::getInstance()->getService( Constants::SERVICE_FEATURE_MANAGER );
	}

	/**
	 * Gets the language service.
	 *
	 * @return LanguageService
	 */
	public static function getLanguageService(): LanguageService {
		return new LanguageService();
	}
}
