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
 * This is a value object for authentication requests with a username,
 * password, and domain
 * @ingroup Auth
 * @since 1.26
 */
abstract class PasswordDomainAuthenticationRequest extends PasswordAuthenticationRequest {
	/** @var string Domain */
	public $domain = null;

	public static function getFieldInfo() {
		$domainList = array();
		foreach ( static::domainList() as $domain ) {
			$domainList[$domain] = new RawMessage( '$1', array( $domain ) );
		}

		return parent::getFieldInfo() + array(
			'domain' => array(
				'type' => 'select',
				'label' => wfMessage( 'authmanager-domain-label' ),
				'help' => wfMessage( 'authmanager-domain-help' ),
				'options' => $domainList,
			),
		);
	}

	/**
	 * Return the list of domains
	 * @note Subclasses must reimplement this static method
	 * @return string[]
	 */
	protected static function domainList() {
		throw new BadMethodCallException( get_called_class() . ' must override domainList()' );
	}
}
