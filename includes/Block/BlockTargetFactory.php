<?php

namespace MediaWiki\Block;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserNameUtils;
use RuntimeException;
use stdClass;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Factory for BlockTarget objects
 *
 * @since 1.44
 */
class BlockTargetFactory implements WikiAwareEntity {
	use WikiAwareEntityTrait;

	private UserIdentityLookup $userIdentityLookup;
	private UserNameUtils $userNameUtils;

	private string|false $wikiId;

	/**
	 * @var array The range block minimum prefix lengths indexed by protocol (IPv4 or IPv6)
	 */
	private $rangePrefixLimits;

	/**
	 * @internal Only for use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::BlockCIDRLimit,
	];

	public function __construct(
		ServiceOptions $options,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils,
		string|false $wikiId = Block::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->rangePrefixLimits = $options->get( MainConfigNames::BlockCIDRLimit );
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userNameUtils = $userNameUtils;
		$this->wikiId = $wikiId;
	}

	/** @inheritDoc */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * Try to create a block target from a user input string.
	 *
	 * @param string|null $str
	 * @return BlockTarget|null
	 */
	public function newFromString( ?string $str ): ?BlockTarget {
		if ( $str === null ) {
			return null;
		}

		$str = trim( $str );

		if ( IPUtils::isValid( $str ) ) {
			return new AnonIpBlockTarget( IPUtils::sanitizeIP( $str ), $this->wikiId );
		} elseif ( IPUtils::isValidRange( $str ) ) {
			return new RangeBlockTarget(
				IPUtils::sanitizeRange( $str ),
				$this->rangePrefixLimits,
				$this->wikiId
			);
		}

		if ( preg_match( '/^#\d+$/', $str ) ) {
			// Autoblock reference in the form "#12345"
			return new AutoBlockTarget(
				(int)substr( $str, 1 ),
				$this->wikiId
			);
		}

		$userFromDB = $this->userIdentityLookup->getUserIdentityByName( $str );
		if ( $userFromDB instanceof UserIdentity ) {
			return new UserBlockTarget( $userFromDB );
		}

		// Wrap the invalid user in a UserIdentityValue.
		// This allows validateTarget() to return a "nosuchusershort" message,
		// which is needed for Special:Block.
		$canonicalName = $this->userNameUtils->getCanonical( $str );
		if ( $canonicalName !== false ) {
			return new UserBlockTarget( new UserIdentityValue( 0, $canonicalName ) );
		}

		return null;
	}

	/**
	 * Try to create a block target from a single IP address
	 *
	 * @param string $ip
	 * @return AnonIpBlockTarget|null
	 */
	public function newFromIp( string $ip ): ?AnonIpBlockTarget {
		if ( IPUtils::isValid( $ip ) ) {
			return new AnonIpBlockTarget( IPUtils::sanitizeIP( $ip ), $this->wikiId );
		}
		return null;
	}

	/**
	 * Create a BlockTarget from a UserIdentity, which may refer to a
	 * registered user, an IP address or range.
	 *
	 * @param UserIdentity $user
	 * @return BlockTarget
	 */
	public function newFromUser( UserIdentity $user ): BlockTarget {
		$this->assertWiki( $user->getWikiId() );
		$name = $user->getName();
		if ( $user->getId( $this->wikiId ) !== 0 ) {
			// We'll trust the caller and skip IP validity checks
			return new UserBlockTarget( $user );
		} elseif ( IPUtils::isValidRange( $name ) ) {
			return $this->newRangeBlockTarget( IPUtils::sanitizeRange( $name ) );
		} elseif ( IPUtils::isValid( $name ) ) {
			return $this->newAnonIpBlockTarget( IPUtils::sanitizeIP( $name ) );
		} else {
			return new UserBlockTarget( $user );
		}
	}

	/**
	 * Try to create a BlockTarget from a UserIdentity|string|null, a union type
	 * previously used as a target by various methods.
	 *
	 * @param UserIdentity|string|null $union
	 * @return BlockTarget|null
	 */
	public function newFromLegacyUnion( $union ): ?BlockTarget {
		if ( $union instanceof UserIdentity ) {
			if ( IPUtils::isValid( $union->getName() ) ) {
				return new AnonIpBlockTarget( IPUtils::sanitizeIP( $union->getName() ), $this->wikiId );
			} else {
				return new UserBlockTarget( $union );
			}
		} elseif ( is_string( $union ) ) {
			return $this->newFromString( $union );
		} else {
			return null;
		}
	}

	/**
	 * Try to create a BlockTarget from a row which must contain bt_user,
	 * bt_address and optionally bt_user_text.
	 *
	 * bt_auto is ignored, so this is suitable for permissions and for block
	 * creation but not for display.
	 *
	 * @param stdClass $row
	 * @return BlockTarget|null
	 */
	public function newFromRowRaw( $row ): ?BlockTarget {
		return $this->newFromRowInternal( $row, false );
	}

	/**
	 * Try to create a BlockTarget from a row which must contain bt_auto,
	 * bt_user, bt_address and bl_id, and optionally bt_user_text.
	 *
	 * If bt_auto is set, the address will be redacted to avoid disclosing it.
	 * The ID will be wrapped in an AutoblockTarget.
	 *
	 * @param stdClass $row
	 * @return BlockTarget|null
	 */
	public function newFromRowRedacted( $row ): ?BlockTarget {
		return $this->newFromRowInternal( $row, true );
	}

	/**
	 * @param stdClass $row
	 * @param bool $redact
	 * @return BlockTarget|null
	 */
	private function newFromRowInternal( $row, $redact ): ?BlockTarget {
		if ( $redact && $row->bt_auto ) {
			return $this->newAutoBlockTarget( $row->bl_id );
		} elseif ( isset( $row->bt_user ) ) {
			if ( isset( $row->bt_user_text ) ) {
				$user = new UserIdentityValue( $row->bt_user, $row->bt_user_text, $this->wikiId );
			} else {
				$user = $this->userIdentityLookup->getUserIdentityByUserId( $row->bt_user );
				if ( !$user ) {
					$user = $this->userIdentityLookup->getUserIdentityByUserId(
						$row->bt_user, IDBAccessObject::READ_LATEST );
					if ( !$user ) {
						throw new RuntimeException(
							"Unable to find name for user ID {$row->bt_user}" );
					}
				}
			}
			return new UserBlockTarget( $user );
		} elseif ( $row->bt_address === null ) {
			return null;
		} elseif ( IPUtils::isValid( $row->bt_address ) ) {
			return $this->newAnonIpBlockTarget( IPUtils::sanitizeIP( $row->bt_address ) );
		} elseif ( IPUtils::isValidRange( $row->bt_address ) ) {
			return $this->newRangeBlockTarget( IPUtils::sanitizeRange( $row->bt_address ) );
		} else {
			return null;
		}
	}

	/**
	 * Create an AutoBlockTarget for the given ID
	 *
	 * A simple constructor proxy for pre-validated input.
	 *
	 * @param int $id
	 * @return AutoBlockTarget
	 */
	public function newAutoBlockTarget( int $id ): AutoBlockTarget {
		return new AutoBlockTarget( $id, $this->wikiId );
	}

	/**
	 * Create a UserBlockTarget for the given user.
	 *
	 * A simple constructor proxy for pre-validated input.
	 *
	 * The user must be a real registered user. Use newFromUser() to create a
	 * block target from a UserIdentity which may represent an IP address.
	 *
	 * @param UserIdentity $user
	 * @return UserBlockTarget
	 */
	public function newUserBlockTarget( UserIdentity $user ): UserBlockTarget {
		$this->assertWiki( $user->getWikiId() );
		if ( IPUtils::isValid( $user->getName() ) ) {
			throw new InvalidArgumentException( 'IP address passed to newUserBlockTarget' );
		}
		return new UserBlockTarget( $user );
	}

	/**
	 * Create an IP block target
	 *
	 * A simple constructor proxy for pre-validated input. Use newFromIP() to
	 * apply normalization, for example stripping leading zeroes.
	 *
	 * @param string $ip
	 * @return AnonIpBlockTarget
	 */
	public function newAnonIpBlockTarget( string $ip ): AnonIpBlockTarget {
		if ( !IPUtils::isValid( $ip ) ) {
			throw new InvalidArgumentException( 'Invalid IP address for block target' );
		}
		return new AnonIpBlockTarget( $ip, $this->wikiId );
	}

	/**
	 * Create a range block target.
	 *
	 * A simple constructor proxy for pre-validated input.
	 *
	 * @param string $cidr
	 * @return RangeBlockTarget
	 */
	public function newRangeBlockTarget( string $cidr ): RangeBlockTarget {
		if ( !IPUtils::isValidRange( $cidr ) ) {
			throw new InvalidArgumentException( 'Invalid IP range for block target' );
		}
		return new RangeBlockTarget( $cidr, $this->rangePrefixLimits, $this->wikiId );
	}
}
