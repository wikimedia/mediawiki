<?php

namespace MediaWiki\Rest\Module;

/**
 * Describes the set of functionality applied to a module by the REST API framework.
 * Module availability is determined by the module's audience designation, and can be overridden
 * by the RestModuleOverrides config variable.
 *
 * Availabilities are backed by strings corresponding to the availability values in the
 * RestModuleOverrides config variable.
 *
 * @since 1.47
 */
enum ModuleAvailability: string {
	case DISABLED = 'disabled';         // treated as if the module does not exist
	case HIDDEN = 'hidden';             // callable, excluded from /discovery and the REST Sandbox
	case DISCOVERABLE = 'discoverable'; // callable, listed in /discovery, excluded from the REST Sandbox
	case PUBLISHED = 'published';       // callable, listed in /discovery and the REST Sandbox

	/**
	 * Gets the availability for a given audience designation.
	 *
	 * @since 1.47
	 *
	 * @param ?AudienceDesignation $ad The audience designation
	 *
	 * @return ModuleAvailability
	 */
	public static function getModuleAvailability( ?AudienceDesignation $ad ): ModuleAvailability {
		if ( $ad === null ) {
			return self::DISABLED;
		}

		return match ( $ad ) {
			AudienceDesignation::PUBLIC => self::PUBLISHED,
			AudienceDesignation::INTERNAL => self::PUBLISHED,
			AudienceDesignation::BETA => self::PUBLISHED,
			AudienceDesignation::NONE => self::PUBLISHED,
		};
	}
}
