<?php

namespace MediaWiki\Rest\Module;

/**
 * Describes the set of functionality applied to a module by the REST API framework.
 * Module modes are determined by the module's audience designation, and can be overridden
 * by the RestModuleOverrides config variable.
 *
 * Module modes are backed by strings corresponding to the modes in the RestModuleOverrides
 * config variable.
 *
 * @since 1.47
 */
enum ModuleMode: string {
	case DISABLED = 'disabled';         // treated as if the module does not exist
	case HIDDEN = 'hidden';             // callable, excluded from /discovery and the REST Sandbox
	case DISCOVERABLE = 'discoverable'; // callable, listed in /discovery, excluded from the REST Sandbox
	case PUBLISHED = 'published';       // callable, listed in /discovery and the REST Sandbox

	/**
	 * Gets the module mode for a given audience designation
	 *
	 * @param ?AudienceDesignation $ad The audience designation
	 *
	 * @return ModuleMode
	 */
	public static function getModuleMode( ?AudienceDesignation $ad ): ModuleMode {
		if ( $ad === null ) {
			return self::DISABLED;
		}

		return match ( $ad ) {
			AudienceDesignation::PUBLISHED => self::PUBLISHED,
			AudienceDesignation::INTERNAL => self::PUBLISHED,
			AudienceDesignation::BETA => self::PUBLISHED
		};
	}

	/**
	 * Gets the mode parameters, if any, for a given audience designation.
	 *
	 * @param ?AudienceDesignation $ad The audience designation
	 *
	 * @return array
	 */
	public static function getModeParams( ?AudienceDesignation $ad ): array {
		$params = [];

		if ( $ad === AudienceDesignation::BETA ) {
			$params['group'] = 'beta';
		}
		if ( $ad === AudienceDesignation::INTERNAL ) {
			$params['group'] = 'internal';
		}

		return $params;
	}
}
