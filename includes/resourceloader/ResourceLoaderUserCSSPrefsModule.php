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
class ResourceLoaderUserCSSPrefsModule extends ResourceLoaderModule {

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
		return $this->modifiedTime[$hash] = wfTimestamp( TS_UNIX, $wgUser->getTouched() );
	}

	/**
	 * @param $context ResourceLoaderContext
	 * @return array
	 */
	public function getStyles( ResourceLoaderContext $context ) {
		global $wgAllowUserCssPrefs, $wgUser;

		if ( !$wgAllowUserCssPrefs ) {
			return array();
		}

		$options = $wgUser->getOptions();

		// Build CSS rules
		$rules = array();

		// Underline: 2 = browser default, 1 = always, 0 = never
		if ( $options['underline'] < 2 ) {
			$rules[] = "a { text-decoration: " .
				( $options['underline'] ? 'underline' : 'none' ) . "; }";
		} else {
			# The scripts of these languages are very hard to read with underlines
			$rules[] = 'a:lang(ar), a:lang(ckb), a:lang(kk-arab), ' .
			'a:lang(mzn), a:lang(ps), a:lang(ur) { text-decoration: none; }';
		}
		if ( $options['justify'] ) {
			$rules[] = "#article, #bodyContent, #mw_content { text-align: justify; }\n";
		}
		if ( !$options['showtoc'] ) {
			$rules[] = "#toc { display: none; }\n";
		}
		if ( !$options['editsection'] ) {
			$rules[] = ".mw-editsection { display: none; }\n";
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
		return array( 'all' => $style );
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return 'private';
	}

	/**
	 * @return array
	 */
	public function getDependencies() {
		return array( 'mediawiki.user' );
	}
}
