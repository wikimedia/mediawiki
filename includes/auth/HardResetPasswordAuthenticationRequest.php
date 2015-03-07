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
 * @ingroup Auth
 */

/**
 * This is a value object for password reset
 * @ingroup Auth
 * @since 1.26
 */
class HardResetPasswordAuthenticationRequest extends PasswordAuthenticationRequest {
	/** @var string Password retype */
	public $retype = null;

	public static function getFieldInfo() {
		return parent::getFieldInfo() + array(
			'retype' => array(
				'type' => 'password',
				'label' => wfMessage( 'authmanager-retype-label' ),
				'help' => wfMessage( 'authmanager-retype-help' ),
			),
		);
	}
}
