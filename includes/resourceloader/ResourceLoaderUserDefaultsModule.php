<?php
/**
 * Resource loader module for default user preferences.
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
 * @author Ori Livneh
 */

/**
 * Module for default user preferences.
 */
class ResourceLoaderUserDefaultsModule extends ResourceLoaderModule {

	/* Protected Members */

	protected $targets = array( 'desktop', 'mobile' );

	/* Methods */

	/**
	 * @param ResourceLoaderContext $context
	 * @return string Hash
	 */
	public function getModifiedHash( ResourceLoaderContext $context ) {
		return md5( serialize( User::getDefaultOptions() ) );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return int
	 */
	public function getModifiedTime( ResourceLoaderContext $context ) {
		return $this->getHashMtime( $context );
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	public function getScript( ResourceLoaderContext $context ) {
		return Xml::encodeJsCall(
			'mw.user.options.set',
			array( User::getDefaultOptions() ),
			ResourceLoader::inDebugMode()
		);
	}
}
