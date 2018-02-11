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
 */

/**
 * Wrapper object for MediaWiki's localization functions,
 * to be passed to the template engine.
 *
 * @private
 * @ingroup Skins
 */
class MediaWikiI18N {
	private $context = [];

	/**
	 * @deprecate since 1.31 Use BaseTemplate::msg() or Skin::msg() instead for setting
	 *  message parameters.
	 */
	function set( $varName, $value ) {
		wfDeprecated( __METHOD__, '1.31' );
		$this->context[$varName] = $value;
	}

	/**
	 * @deprecate since 1.31 Use BaseTemplate::msg(), Skin::msg(), or wfMessage() instead.
	 */
	function translate( $value ) {
		wfDeprecated( __METHOD__, '1.31' );
		// Hack for i18n:attributes in PHPTAL 1.0.0 dev version as of 2004-10-23
		$value = preg_replace( '/^string:/', '', $value );

		$value = wfMessage( $value )->text();
		// interpolate variables
		$m = [];
		while ( preg_match( '/\$([0-9]*?)/sm', $value, $m ) ) {
			list( $src, $var ) = $m;
			Wikimedia\suppressWarnings();
			$varValue = $this->context[$var];
			Wikimedia\restoreWarnings();
			$value = str_replace( $src, $varValue, $value );
		}
		return $value;
	}
}
