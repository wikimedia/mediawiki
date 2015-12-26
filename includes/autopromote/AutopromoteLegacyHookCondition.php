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
 * @since 1.28
 */

/**
 * A legacy implementation for the hook
 */
class AutopromoteLegacyHookCondition extends AutopromoteConditionBase {

	public function getName() {
		return 'hook';
	}

	public function evaluate( User $user ) {
		$params = $this->getParameter();
		$result = null;
		Hooks::run( 'AutopromoteCondition', [ $params[0],
			array_slice( $params, 1 ), $user, &$result ] );
		if ( $result === null ) {
			throw new MWException( "Unrecognized condition {$params[0]} for autopromotion!" );
		}

		return (bool)$result;
	}
}
