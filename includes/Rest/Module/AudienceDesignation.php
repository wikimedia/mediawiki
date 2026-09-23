<?php

namespace MediaWiki\Rest\Module;

/**
 * Describes the set of audience designations available to REST modules.
 * Modules without a specific designation are assumed to be "public".
 *
 * @since 1.47
 */
enum AudienceDesignation: string {
	// This is the default if no audience designation is specified. We therefore don't expect any
	// module to actually specify this (although it would work as expected if one did).
	case PUBLIC = 'public';

	case INTERNAL = 'internal';

	case BETA = 'beta';

	// The prefix-less flat route module has no audience designation at all, which is valid
	// (unlike a malformed id, represented by null). The empty backing value can never be
	// produced by a module id suffix, so no module can declare itself as NONE. It is not meant
	// to be output as an audience string.
	case NONE = '';

	/**
	 * Gets a module's audience designation from its module id.
	 *
	 * @param string $moduleId The module id
	 *
	 * @return ?AudienceDesignation
	 */
	public static function fromModuleId( string $moduleId ): ?AudienceDesignation {
		if ( $moduleId === '' ) {
			return self::NONE;
		}

		// Module ids with no audience designation are assumed to be "public".
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
			return self::PUBLIC;
		}

		// The leading character of $matches[2] is guaranteed to be a dash. Strip it.
		$adStr = substr( $matches[2], 1 );
		return self::tryFrom( $adStr );
	}

	/**
	 * Gets the default REST discovery groups for this audience designation. Every published
	 * module is expected to have at least one group (T429399), so modules without an audience
	 * fall back to the generic 'default' group rather than an empty list.
	 *
	 * @return string[]
	 */
	public function getDefaultGroups(): array {
		return match ( $this ) {
			self::PUBLIC => [ 'preferred' ],
			self::INTERNAL => [ 'internal' ],
			self::BETA => [ 'beta' ],
			self::NONE => [ 'default' ],
		};
	}
}
