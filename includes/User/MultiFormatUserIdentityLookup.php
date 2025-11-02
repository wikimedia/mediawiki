<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\IPUtils;

/**
 * A service to look up user identities based on the user input.
 * This class is similar to UserIdentityLookup but allows to search for users using
 * multiple input formats (e.g. user ID, user name, user name with interwiki suffix).
 *
 * This service is designed to be of higher level than UserIdentityLookup and handy in
 * places like special pages or API modules where the input can be in multiple formats.
 * Additionally, this service allows to check for user rights, e.g. whether a hidden user
 * should be returned or not.
 *
 * @since 1.45
 * @ingroup User
 */
class MultiFormatUserIdentityLookup {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::LocalDatabases,
		MainConfigNames::UserrightsInterwikiDelimiter
	];

	public function __construct(
		private readonly ActorStoreFactory $actorStoreFactory,
		private readonly UserFactory $userFactory,
		private readonly ServiceOptions $options,
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Looks up for a user identity based on the passed designator string.
	 *
	 * The designator can be in one of the following formats:
	 * - username (e.g. "Example"),
	 * - user ID (e.g. "#123"),
	 * - username or id with interwiki suffix (e.g. "Example@wiki", "#123@wiki"), where the separator
	 *   between those two parts is defined by the `wgUserrightsInterwikiDelimiter` config option.
	 *
	 * For interwiki users, this service assures that the remote wiki exists and is considered
	 * a local database (i.e. is listed in `wgLocalDatabases`).
	 * @param string $designator
	 * @param Authority|null $viewer
	 * @return Status<UserIdentity>
	 */
	public function getUserIdentity( string $designator, ?Authority $viewer = null ): Status {
		[ $name, $wikiId ] = $this->parseUserDesignator( $designator );

		// Check if the wikiId is valid
		if ( $wikiId !== UserIdentity::LOCAL ) {
			$localDatabases = $this->options->get( MainConfigNames::LocalDatabases );
			if ( !in_array( $wikiId, $localDatabases ) ) {
				return Status::newFatal( 'userrights-nodatabase', $wikiId );
			}
		}

		if ( $name === '' ) {
			return Status::newFatal( 'nouserspecified' );
		}

		if ( IPUtils::isValid( $name ) ) {
			return Status::newGood( UserIdentityValue::newAnonymous( $name, $wikiId ) );
		}

		$userIdentityLookup = $this->actorStoreFactory->getUserIdentityLookup( $wikiId );
		if ( $name[0] == '#' ) {
			$id = intval( substr( $name, 1 ) );
			$user = $userIdentityLookup->getUserIdentityByUserId( $id );
		} else {
			$user = $userIdentityLookup->getUserIdentityByName( $name );
		}

		if ( !$user ) {
			return Status::newFatal( 'nosuchusershort', $designator );
		}

		// If an authority is specified, check if the viewer is allowed to see the user
		// If they can't, pretend the user doesn't exist
		if (
			$viewer !== null &&
			$user->getWikiId() === UserIdentity::LOCAL &&
			$this->userFactory->newFromUserIdentity( $user )->isHidden() &&
			!$viewer->isAllowed( 'hideuser' )
		) {
			return Status::newFatal( 'nosuchusershort', $designator );
		}

		return Status::newGood( $user );
	}

	/**
	 * Parses the user designator into the name and wiki parts.
	 * If the wikiId refers to local wiki, ensure that the wikiId is set to UserIdentity::LOCAL.
	 * @return array{0:string,1:string|false} [name, wikiId]
	 */
	private function parseUserDesignator( string $designator ): array {
		$interwikiSeparator = $this->options->get( MainConfigNames::UserrightsInterwikiDelimiter );
		$designatorParts = explode( $interwikiSeparator, $designator );

		$name = trim( $designator );
		$wikiId = UserIdentity::LOCAL;
		if ( count( $designatorParts ) >= 2 ) {
			$name = trim( $designatorParts[0] );
			$wikiId = trim( $designatorParts[1] );

			if ( WikiMap::isCurrentWikiId( $wikiId ) ) {
				$wikiId = UserIdentity::LOCAL;
			}
		}
		return [ $name, $wikiId ];
	}
}
