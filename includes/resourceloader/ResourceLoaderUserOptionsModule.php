<?php
/**
 * ResourceLoader module for user preference customizations.
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

	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * @param ResourceLoaderContext $context
	 * @return array List of module names as strings
	 */
	public function getDependencies( ResourceLoaderContext $context = null ) {
		return [ 'user.defaults' ];
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string
	 */
	public function getScript( ResourceLoaderContext $context ) {
		return Xml::encodeJsCall( 'mw.user.options.set',
			[ $context->getUserObj()->getOptions( User::GETOPTIONS_EXCLUDE_DEFAULTS ) ],
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
	public function getPosition() {
		return 'top';
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'private';
	}
}
