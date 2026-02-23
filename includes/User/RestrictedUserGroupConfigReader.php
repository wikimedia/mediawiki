<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\WikiMap\WikiMap;

/**
 * A helper class to read the restricted user groups configuration for a given wiki.
 *
 * @since 1.46
 */
class RestrictedUserGroupConfigReader {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RestrictedGroups,
	];

	public function __construct(
		private readonly ServiceOptions $options
	) {
	}

	/**
	 * Reads the restricted group configuration for the specified wiki, either from the ServiceOptions provided to
	 * the constructor (if the wiki is the local wiki) or from the global $wgConf variable (otherwise).
	 * @param false|string $wiki The wiki ID for which to read the configuration. `false` means the current wiki.
	 * @return array<string, UserGroupRestrictions> An array mapping group names to their restrictions.
	 */
	public function getConfig( false|string $wiki = false ): array {
		$isLocal = $wiki === false || $wiki === WikiMap::getCurrentWikiId();
		if ( $isLocal ) {
			$rawConfig = $this->getConfigForLocalWiki();
		} else {
			$rawConfig = $this->getConfigForRemoteWiki( $wiki );
		}

		return array_map(
			static fn ( $groupRestrictions ) => new UserGroupRestrictions( $groupRestrictions ),
			$rawConfig
		);
	}

	private function getConfigForLocalWiki(): array {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		return $this->options->get( MainConfigNames::RestrictedGroups );
	}

	private function getConfigForRemoteWiki( string $wiki ): array {
		global $wgConf;
		'@phan-var \MediaWiki\Config\SiteConfiguration $wgConf';

		return $wgConf->get( 'wgRestrictedGroups', $wiki ) ?? [];
	}
}
