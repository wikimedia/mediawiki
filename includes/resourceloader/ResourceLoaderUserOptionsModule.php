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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Module for user preference customizations
 */
class ResourceLoaderUserOptionsModule extends ResourceLoaderModule {

	/* Protected Members */

	protected $modifiedTime = array();

	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	/* Methods */

	/**
	 * @param $context ResourceLoaderContext
	 * @return array|int|Mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$hash = $context->getHash();
		if ( isset( $this->modifiedTime[$hash] ) ) {
			return $this->modifiedTime[$hash];
		}

		global $wgUser;

		if ( $context->getUser() === $wgUser->getName() ) {
			return $this->modifiedTime[$hash] = wfTimestamp( TS_UNIX, $wgUser->getTouched() );
		} else {
			return 1;
		}
	}

	/**
	 * Fetch the context's user options, or if it doesn't match current user,
	 * the default options.
	 *
	 * @param $context ResourceLoaderContext: Context object
	 * @return Array: List of user options keyed by option name
	 */
	protected function contextUserOptions( ResourceLoaderContext $context ) {
		global $wgUser;

		// Verify identity -- this is a private module
		if ( $context->getUser() === $wgUser->getName() ) {
			return $wgUser->getOptions();
		} else {
			return User::getDefaultOptions();
		}
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return string
	 */
	public function getScript( ResourceLoaderContext $context ) {
		return Xml::encodeJsCall( 'mw.user.options.set',
			array( $this->contextUserOptions( $context ) ) );
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'private';
	}
}
