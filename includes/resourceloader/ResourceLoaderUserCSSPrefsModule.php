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
class ResourceLoaderUserCSSPrefsModule extends ResourceLoaderModule {

	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		if ( !$this->getConfig()->get( 'AllowUserCssPrefs' ) ) {
			return [];
		}

		$options = $context->getUserObj()->getOptions();

		// Build CSS rules
		$rules = [];

		// Underline: 2 = skin default, 1 = always, 0 = never
		if ( $options['underline'] < 2 ) {
			$rules[] = "a { text-decoration: " .
				( $options['underline'] ? 'underline' : 'none' ) . "; }";
		}
		if ( $options['editfont'] !== 'default' ) {
			// Double-check that $options['editfont'] consists of safe characters only
			if ( preg_match( '/^[a-zA-Z0-9_, -]+$/', $options['editfont'] ) ) {
				$rules[] = "textarea { font-family: {$options['editfont']}; }\n";
			}
		}
		$style = implode( "\n", $rules );
		if ( $this->getFlip( $context ) ) {
			$style = CSSJanus::transform( $style, true, false );
		}
		return [ 'all' => $style ];
	}

	/**
	 * @param ResourceLoaderContext $context
	 * @return bool
	 */
	public function isKnownEmpty( ResourceLoaderContext $context ) {
		$styles = $this->getStyles( $context );
		return isset( $styles['all'] ) && $styles['all'] === '';
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'private';
	}
}
