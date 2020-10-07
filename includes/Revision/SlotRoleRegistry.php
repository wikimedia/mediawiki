<?php
/**
 * This file is part of MediaWiki.
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

namespace MediaWiki\Revision;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\NameTableStore;
use Wikimedia\Assert\Assert;

/**
 * A registry service for SlotRoleHandlers, used to define which slot roles are available on
 * which page.
 *
 * Extensions may use the SlotRoleRegistry to register the slots they define.
 *
 * In the context of the SlotRoleRegistry, it is useful to distinguish between "defined" and "known"
 * slot roles: A slot role is "defined" if defineRole() or defineRoleWithModel() was called for
 * that role. A slot role is "known" if the NameTableStore provided to the constructor as the
 * $roleNamesStore parameter has an ID associated with that role, which essentially means that
 * the role at some point has been used on the wiki. Roles that are not "defined" but are
 * "known" typically belong to extensions that used to be installed on the wiki, but no longer are.
 * Such slots should be considered ok for display and administrative operations, but only "defined"
 * slots should be supported for editing.
 *
 * @since 1.33
 */
class SlotRoleRegistry {

	/**
	 * @var NameTableStore
	 */
	private $roleNamesStore;

	/**
	 * @var callable[]
	 */
	private $instantiators = [];

	/**
	 * @var SlotRoleHandler[]
	 */
	private $handlers;

	/**
	 * @param NameTableStore $roleNamesStore
	 */
	public function __construct( NameTableStore $roleNamesStore ) {
		$this->roleNamesStore = $roleNamesStore;
	}

	/**
	 * Defines a slot role.
	 *
	 * For use by extensions that wish to define roles beyond the main slot role.
	 *
	 * @see defineRoleWithModel()
	 *
	 * @param string $role The role name of the slot to define. This should follow the
	 *        same convention as message keys:
	 * @param callable $instantiator called with $role as a parameter;
	 *        Signature: function ( string $role ): SlotRoleHandler
	 */
	public function defineRole( $role, callable $instantiator ) {
		$role = strtolower( $role );

		if ( $this->isDefinedRole( $role ) ) {
			throw new LogicException( "Role $role is already defined" );
		}

		$this->instantiators[$role] = $instantiator;
	}

	/**
	 * Defines a slot role that allows only the given content model, and has no special
	 * behavior.
	 *
	 * For use by extensions that wish to define roles beyond the main slot role, but have
	 * no need to implement any special behavior for that slot.
	 *
	 * @see defineRole()
	 *
	 * @param string $role The role name of the slot to define, see defineRole()
	 *        for more information.
	 * @param string $model A content model name, see ContentHandler
	 * @param array $layout See SlotRoleHandler getOutputLayoutHints
	 */
	public function defineRoleWithModel( $role, $model, $layout = [] ) {
		$role = strtolower( $role );

		$this->defineRole(
			$role,
			function ( $role ) use ( $model, $layout ) {
				return new SlotRoleHandler( $role, $model, $layout );
			}
		);
	}

	/**
	 * Gets the SlotRoleHandler that should be used when processing content of the given role.
	 *
	 * @param string $role
	 *
	 * @throws InvalidArgumentException If $role is not a known slot role.
	 * @return SlotRoleHandler The handler to be used for $role. This may be a
	 *         FallbackSlotRoleHandler if the slot is "known" but not "defined".
	 */
	public function getRoleHandler( $role ) {
		$role = strtolower( $role );

		if ( !isset( $this->handlers[$role] ) ) {
			if ( !$this->isDefinedRole( $role ) ) {
				if ( $this->isKnownRole( $role ) ) {
					// The role has no handler defined, but is represented in the database.
					// This may happen e.g. when the extension that defined the role was uninstalled.
					wfWarn( __METHOD__ . ": known but undefined slot role $role" );
					$this->handlers[$role] = new FallbackSlotRoleHandler( $role );
				} else {
					// The role doesn't have a handler defined, and is not represented in
					// the database. Something must be quite wrong.
					throw new InvalidArgumentException( "Unknown role $role" );
				}
			} else {
				$handler = call_user_func( $this->instantiators[$role], $role );

				Assert::postcondition(
					$handler instanceof SlotRoleHandler,
					"Instantiator for $role role must return a SlotRoleHandler"
				);

				$this->handlers[$role] = $handler;
			}
		}

		return $this->handlers[$role];
	}

	/**
	 * Returns the list of roles allowed when creating a new revision on the given page.
	 * The choice should not depend on external state, such as the page content.
	 * Note that existing revisions of that page are not guaranteed to comply with this list.
	 *
	 * All implementations of this method are required to return at least all "required" roles.
	 *
	 * @param LinkTarget $title
	 *
	 * @return string[]
	 */
	public function getAllowedRoles( LinkTarget $title ) {
		// TODO: allow this to be overwritten per namespace (or page type)
		// TODO: decide how to control which slots are offered for editing per default (T209927)
		return $this->getDefinedRoles();
	}

	/**
	 * Returns the list of roles required when creating a new revision on the given page.
	 * The should not depend on external state, such as the page content.
	 * Note that existing revisions of that page are not guaranteed to comply with this list.
	 *
	 * All required roles are implicitly considered "allowed", so any roles
	 * returned by this method will also be returned by getAllowedRoles().
	 *
	 * @param LinkTarget $title
	 *
	 * @return string[]
	 */
	public function getRequiredRoles( LinkTarget $title ) {
		// TODO: allow this to be overwritten per namespace (or page type)
		return [ 'main' ];
	}

	/**
	 * Returns the list of roles defined by calling defineRole().
	 *
	 * This list should be used when enumerating slot roles that can be used for editing.
	 *
	 * @return string[]
	 */
	public function getDefinedRoles() {
		return array_keys( $this->instantiators );
	}

	/**
	 * Returns the list of known roles, including the ones returned by getDefinedRoles(),
	 * and roles that exist according to the NameTableStore provided to the constructor.
	 *
	 * This list should be used when enumerating slot roles that can be used in queries or
	 * for display.
	 *
	 * @return string[]
	 */
	public function getKnownRoles() {
		return array_unique( array_merge(
			$this->getDefinedRoles(),
			$this->roleNamesStore->getMap()
		) );
	}

	/**
	 * Whether the given role is defined, that is, it was defined by calling defineRole().
	 *
	 * @param string $role
	 * @return bool
	 */
	public function isDefinedRole( $role ) {
		$role = strtolower( $role );
		return in_array( $role, $this->getDefinedRoles(), true );
	}

	/**
	 * Whether the given role is known, that is, it's either defined or exist according to
	 * the NameTableStore provided to the constructor.
	 *
	 * @param string $role
	 * @return bool
	 */
	public function isKnownRole( $role ) {
		$role = strtolower( $role );
		return in_array( $role, $this->getKnownRoles(), true );
	}

}
