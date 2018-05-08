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

use Wikimedia\Assert\Assert;

/**
 * Value object representing a user's identity.
 *
 * @since 1.31
 */
class UserIdentityValue implements UserIdentity {

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

	/**
	 * @param int $id
	 * @param string $name
	 * @param int $actor
	 */
	public function __construct( $id, $name, $actor ) {
		Assert::parameterType( 'integer', $id, '$id' );
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'integer', $actor, '$actor' );

		$this->id = $id;
		$this->name = $name;
		$this->actor = $actor;
	}

	/**
	 * @return int The user ID. May be 0 for anonymous users or for users with no local account.
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string The user's logical name. May be an IPv4 or IPv6 address for anonymous users.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return int The user's actor ID. May be 0 if no actor ID has been assigned.
	 */
	public function getActorId() {
		return $this->actor;
	}

	/**
	 * @since 1.32
	 *
	 * @param UserIdentity $user
	 * @return bool
	 */
	public function equals( UserIdentity $user ) {
		// XXX it's not clear whether central ID providers are supposed to obey this
		return $this->getName() === $user->getName();
	}

}
