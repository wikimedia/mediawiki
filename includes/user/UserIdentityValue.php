<?php
/**
 * Value object representing a user's identity.
 *
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
 *
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\DAO\WikiAwareEntityTrait;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PostconditionException;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDatabase;

/**
 * Value object representing a user's identity.
 *
 * @newable
 *
 * @since 1.31
 */
class UserIdentityValue implements UserIdentity {
	use WikiAwareEntityTrait;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var int
	 */
	private $actor;

	/** @var bool|string */
	private $wikiId;

	/**
	 * @stable to call
	 *
	 * @param int $id user ID
	 * @param string $name user name
	 * @param int $actor actor ID
	 * @param string|false $wikiId wiki ID or self::LOCAL for the local wiki
	 */
	public function __construct( $id, $name, $actor, $wikiId = self::LOCAL ) {
		Assert::parameterType( 'integer', $id, '$id' );
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'integer', $actor, '$actor' );
		$this->assertWikiIdParam( $wikiId );

		$this->id = $id;
		$this->name = $name;
		$this->actor = $actor;
		$this->wikiId = $wikiId;
	}

	/**
	 * Get the ID of the wiki this UserIdentity belongs to.
	 *
	 * @since 1.36
	 * @see RevisionRecord::getWikiId()
	 *
	 * @return string|false The wiki's logical name or self::LOCAL to indicate the local wiki
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * The numerical user ID provided to the constructor.
	 *
	 * @return int The user ID. May be 0 for anonymous users or for users with no local account.
	 *
	 * @deprecated since 1.36, use getUserId() instead
	 */
	public function getId() : int {
		$this->deprecateInvalidCrossWiki( self::LOCAL, '1.36' );
		return $this->getUserId();
	}

	/**
	 * @since 1.36
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *        Use self::LOCAL for the local wiki.
	 *
	 * @return int The user id. May be 0 for anonymous users or for users with no local account.
	 *
	 * @throws PreconditionException if $wikiId mismatches $this->getWikiId()
	 */
	public function getUserId( $wikiId = self::LOCAL ) : int {
		$this->assertWiki( $wikiId );
		return $this->id;
	}

	/**
	 * @return string The user's logical name. May be an IPv4 or IPv6 address for anonymous users.
	 */
	public function getName() : string {
		return $this->name;
	}

	/**
	 * The numerical actor ID provided to the constructor or 0 if no actor ID has been assigned.
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *        Omit if expecting the local wiki. Since 1.36.
	 *
	 * @return int The user's actor ID. May be 0 if no actor ID has been assigned.
	 */
	public function getActorId( $wikiId = self::LOCAL ) : int {
		// need to check if $wikiId is an instance of IDatabase, since for legacy reasons,
		// some callers call this function on a UserIdentity passing an IDatabase
		if ( $wikiId instanceof IDatabase ) {
			wfDeprecatedMsg( 'Passing parameter of type IDatabase', '1.36' );
			if ( $this->actor === 0 ) {
				// Passing an IDatabase was never actually allowed on this class.
				// We just support it to prevent hard breakage in places where callers where
				// assuming they got a User, when they actually just have a UserIdentity.
				// However, the caller is expecting to get back a valid actorId. Not getting one
				// may cause data corruption.
				throw new PostconditionException(
					'Caller expects non-zero actor ID.'
				);
			}
		} else {
			$this->deprecateInvalidCrossWiki( $wikiId, '1.36' );
		}
		return $this->actor;
	}

	/**
	 * @since 1.32
	 *
	 * @param UserIdentity $user
	 * @return bool
	 */
	public function equals( UserIdentity $user ) : bool {
		// XXX it's not clear whether central ID providers are supposed to obey this
		return $this->getName() === $user->getName();
	}

	/**
	 * @since 1.34
	 *
	 * @return bool True if user is registered on this wiki, i.e., has a user ID. False if user is
	 *   anonymous or has no local account (which can happen when importing). This is equivalent to
	 *   getId() != 0 and is provided for code readability.
	 */
	public function isRegistered() : bool {
		return $this->getId() != 0;
	}

	public function __toString(): string {
		return $this->getName();
	}

	/**
	 * Sets the actor id.
	 *
	 * This method is deprecated upon introduction. It only exists for transition to ActorStore,
	 * and will be removed shortly - T274148
	 *
	 * @internal
	 * @deprecated since 1.36
	 * @param int $actorId
	 */
	public function setActorId( int $actorId ) {
		$this->actor = $actorId;
	}
}
