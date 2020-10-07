<?php
/**
 * HTML validation and correction
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
 * @ingroup Parser
 */

/**
 * Class to interact with and configure Remex tidy
 *
 * @ingroup Parser
 */
class MWTidy {
	private static $instance;

	/**
	 * Interface with Remex tidy.
	 * If tidy isn't able to correct the markup, the original will be
	 * returned in all its glory with a warning comment appended.
	 *
	 * @param string $text HTML input fragment. This should not contain a
	 *                     <body> or <html> tag.
	 * @return string Corrected HTML output
	 * @throws MWException
	 */
	public static function tidy( $text ) {
		$driver = self::singleton();
		return $driver->tidy( $text );
	}

	/**
	 * @return bool
	 * @deprecated since 1.35; tidy is always enabled
	 */
	public static function isEnabled() {
		return true;
	}

	/**
	 * @return bool|\MediaWiki\Tidy\TidyDriverBase
	 * @deprecated since 1.35; use MWTidy::tidy()
	 */
	public static function singleton() {
		global $wgTidyConfig;
		if ( self::$instance === null ) {
			self::$instance = self::factory( $wgTidyConfig );
		}
		return self::$instance;
	}

	/**
	 * Create a new Tidy driver object from configuration.
	 * @see $wgTidyConfig
	 * @param array|null $config Optional since 1.33
	 * @return bool|\MediaWiki\Tidy\TidyDriverBase
	 * @throws MWException
	 * @internal
	 */
	public static function factory( array $config = null ) {
		return new MediaWiki\Tidy\RemexDriver( $config ?? [] );
	}

	/**
	 * Destroy the current singleton instance
	 * @internal
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}
}
