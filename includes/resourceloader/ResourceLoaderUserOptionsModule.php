<?php
/**
 * Resource loader module for user preference customizations.
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

	protected $targets = array( 'desktop', 'mobile' );

	/* Methods */

	/**
	 * @param ResourceLoaderContext $context
	 * @return array|int|mixed
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		$hash = $context->getHash();
		if ( !isset( $this->modifiedTime[$hash] ) ) {
			global $wgUser;
			$this->modifiedTime[$hash] = wfTimestamp( TS_UNIX, $wgUser->getTouched() );
		}

		return $this->modifiedTime[$hash];
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	public function getScript( ResourceLoaderContext $context ) {
		global $wgUser;
		return Xml::encodeJsCall( 'mw.user.options.set',
			array( $wgUser->getOptions() ),
			ResourceLoader::inDebugMode()
		);
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'private';
	}
}
