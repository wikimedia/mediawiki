<?php
/**
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

/**
 * Show an error when a user tries to do something they do not have the necessary
 * permissions for.
 *
 * @since 1.18
 * @ingroup Exception
 */
class PermissionsError extends ErrorPageError {
	public $permission, $errors;

	public function __construct( $permission, $errors = array() ) {
		global $wgLang;

		$this->permission = $permission;

		if ( !count( $errors ) ) {
			$groups = array_map(
				array( 'User', 'makeGroupLinkWiki' ),
				User::getGroupsWithPermission( $this->permission )
			);

			if ( $groups ) {
				$errors[] = array( 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) );
			} else {
				$errors[] = array( 'badaccess-group0' );
			}
		}

		$this->errors = $errors;
	}

	public function report() {
		global $wgOut;

		$wgOut->showPermissionsErrorPage( $this->errors, $this->permission );
		$wgOut->output();
	}
}
