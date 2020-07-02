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
 * Module for per-user private data that is transmitted on all HTML web responses.
 *
 * It is send to the browser from the HTML <head>. See OutputPage.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderUserOptionsModule extends ResourceLoaderModule {
	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * @param ResourceLoaderContext|null $context
	 * @return string[] List of module names
	 */
	public function getDependencies( ResourceLoaderContext $context = null ) {
		return [ 'user.defaults' ];
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$user = $context->getUserObj();

		$tokens = [
			'patrolToken' => $user->getEditToken( 'patrol' ),
			'watchToken' => $user->getEditToken( 'watch' ),
			'csrfToken' => $user->getEditToken(),
		];
		$script = 'mw.user.tokens.set(' . $context->encodeJson( $tokens ) . ');';

		$options = $user->getOptions( User::GETOPTIONS_EXCLUDE_DEFAULTS );
		// Optimisation: Only output this function call if the user has non-default settings.
		if ( $options ) {
			$script .= 'mw.user.options.set(' . $context->encodeJson( $options ) . ');';
		}

		// Use FILTER_NOMIN annotation to prevent needless minification and caching (T84960).
		return ResourceLoader::FILTER_NOMIN . $script;
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
