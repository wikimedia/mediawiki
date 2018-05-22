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

namespace MediaWiki\Slots;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\SlotRecord;

/**
 * FIXME
 *
 * @since 1.32
 */
class SlotRoleRegistry {

	/**
	 * @var NameTableStore
	 */
	private $roleNamesStore;

	/**
	 * @var array[]
	 */
	private $roleDefinitions;

	/**
	 * Defines a role.
	 *
	 * For use by extensions that wish to define roles beyond the main slot role.
	 *
	 * @param string $role
	 * @param string $model (ignored if $role is 'main')
	 * @param callable $instantiator called with $role as a parameter.
	 */
	public function defineRole( $role, $model, callable $instantiator ) {
		// FIXME
	}

	/**
	 * @param string $role
	 * @param LinkTarget $title
	 *
	 * @return SlotRoleHandler
	 *
	 */
	public function getRoleHandler( $role, LinkTarget $title ) {
		// XXX $title could be a PageIdentity
		// FIXME
	}

	/**
	 * @param string $role
	 * @param LinkTarget $title
	 *
	 * @return string
	 */
	public function getContentModel( $role, LinkTarget $title ) {
		// XXX $title could be a PageIdentity
		// NOTE: for the main slot, use the page model (for now)
		// FIXME
	}

	/**
	 * Returns the list of roles allowed when creating a new revision on the given page.
	 *
	 * @param LinkTarget $title
	 *
	 * @return string[]
	 */
	public function getAllowedRoles( LinkTarget $title ) {
		// XXX $title could be a PageIdentity
		// TODO: allow this to be overwritten per namespace or page type
		return $this->getDefinedRoles();
	}

	/**
	 * Returns the list of roles required when creating a new revision on the given page.
	 *
	 * @param LinkTarget $title
	 *
	 * @return string[]
	 */
	public function getRequiredRoles( LinkTarget $title ) {
		// XXX $title could be a PageIdentity
		// TODO: allow this to be overwritten per namespace or page type
		return [ 'main' ];
	}

	/**
	 * Returns the list of roles defined by calling defineRole().
	 *
	 * @return string[]
	 */
	public function getDefinedRoles() {
		return array_keys( $this->roleDefinitions );
	}

	/**
	 * Returns the list of known roles, including the ones returned by getDefinedRoles(),
	 * and roles that exist in the database.
	 *
	 * @return string[]
	 */
	public function getKnownRoles() {
		return array_unique( array_merge(
			$this->getDefinedRoles(),
			array_keys( $this->roleNamesStore->getMap() )
		) );
	}

}
