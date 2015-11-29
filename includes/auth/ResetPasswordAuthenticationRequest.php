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

namespace MediaWiki\Auth;

/**
 * This is a value object for password reset
 * @ingroup Auth
 * @since 1.27
 */
class ResetPasswordAuthenticationRequest extends PasswordAuthenticationRequest {
	/** @var string Password retype */
	public $retype = null;

	/** @var bool skip option for soft reset */
	public $skip = false;

	/** @var bool See isHard() */
	protected $hard;

	public function __construct( $hard ) {
		$this->hard = $hard;
	}

	/**
	 * Tells whether this request is for a hard reset or a soft reset. Soft reset is optional
	 * and can be bypassed by checking a box; hard reset prevents authentication until performed.
	 * @return bool
	 */
	public function isHard() {
		return $this->hard;
	}

	public function getFieldInfo() {
		$ret = parent::getFieldInfo() + array(
			'retype' => array(
				'type' => 'password',
				'label' => wfMessage( 'authmanager-retype-label' ),
				'help' => wfMessage( 'authmanager-retype-help' ),
			),
		);
		unset( $ret['username'] );

		if ( !$this->hard ) {
			foreach ( $ret as &$field ) {
				$field['optional'] = true;
			}
			$ret += array(
				'skip' => array(
					'type' => 'button',
					'label' => wfMessage( 'authmanager-skip-label' ),
					'help' => wfMessage( 'authmanager-skip-help' ),
					'optional' => true,
				),
			);
		}

		return $ret;
	}

	public function loadFromSubmission( array $data ) {
		if ( !parent::loadFromSubmission( $data ) ) {
			return false;
		}

		// the user must either check skip or provide a new password
		return  $this->skip || $this->username && $this->password;
	}

	public static function __set_state( $data ) {
		$ret = new static( $data['hard'] );
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}
}
