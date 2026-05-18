<?php

namespace MediaWiki\Rest\Module;

/**
 * Describes the set of audience designations available to REST modules.
 * Modules without a specific designation are assumed to be "published".
 *
 * @since 1.47
 */
enum AudienceDesignation: string {
	// This is the default if no audience designation is specified. We therefore don't expect any
	// module to actually specify this (although it would work as expected if one did).
	case PUBLISHED = 'published';

	case INTERNAL = 'internal';

	case BETA = 'beta';

	/**
	 * Gets a module's audience designation from its module id.
	 *
	 * @param string $moduleId The module id
	 *
	 * @return ?AudienceDesignation
	 */
	public static function fromModuleId( string $moduleId ): ?AudienceDesignation {
		// Module ids with no audience designation are assumed to be "published".
		//
		// Return null for module ids of invalid format, or whose audience designation is present
		// but unrecognized. Generally, structure tests should identify invalid module ids and
		// audience designations, so this should be a rare case.
		$pattern = '!^([-.\w]+)/v[0-9]+(-[a-zA-Z]+)?(?:[0-9]+)?$!';
		if ( !preg_match( $pattern, $moduleId, $matches ) ) {
			return null;
		}

		// $match[1] is the (required) module name, e.g. the "mymodule" in "mymodule/v1-beta".
		if ( !isset( $matches[1] ) ) {
			return null;
		}

		// $match[2], if present, is the audience designation, including its leading dash.
		// For example, the "-beta" in "mymodule/v1-beta".
		if ( !isset( $matches[2] ) ) {
			return self::PUBLISHED;
		}

		// The leading character of $matches[2] is guaranteed to be a dash. Strip it.
		$adStr = substr( $matches[2], 1 );
		return self::tryFrom( $adStr );
	}
}
