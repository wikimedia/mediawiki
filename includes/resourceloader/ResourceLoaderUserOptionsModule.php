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
 * Module for user preferences.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderUserOptionsModule extends ResourceLoaderModule {

	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * @param ResourceLoaderContext|null $context
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
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		// Use FILTER_NOMIN annotation to prevent needless minification and caching (T84960).
		return ResourceLoader::FILTER_NOMIN
			. 'mw.user.options.set('
			. $context->encodeJson(
				$context->getUserObj()->getOptions( User::GETOPTIONS_EXCLUDE_DEFAULTS )
			)
			. ');';
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		return !$context->getUserObj()->getOptions( User::GETOPTIONS_EXCLUDE_DEFAULTS );
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'private';
	}
}
