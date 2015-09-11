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
 * Class to interact with HTML tidy
 *
 * Either the external tidy program or the in-process tidy extension
 * will be used depending on availability. Override the default
 * $wgTidyInternal setting to disable the internal if it's not working.
 *
 * @ingroup Parser
 */
class MWTidy {
	private static $instance;

	/**
	 * Interface with html tidy.
	 * If tidy isn't able to correct the markup, the original will be
	 * returned in all its glory with a warning comment appended.
	 *
	 * @param string $text HTML input fragment. This should not contain a
	 *                     <body> or <html> tag.
	 * @return string Corrected HTML output
	 */
	public static function tidy( $text ) {
		$driver = self::singleton();
		if ( !$driver ) {
			throw new MWException( __METHOD__.
				': tidy is disabled, caller should have checked MWTidy::isEnabled()' );
		}
		return $driver->tidy( $text );
	}

	/**
	 * Check HTML for errors, used if $wgValidateAllHtml = true.
	 *
	 * @param string $text
	 * @param string &$errorStr Return the error string
	 * @return bool Whether the HTML is valid
	 */
	public static function checkErrors( $text, &$errorStr = null ) {
		$driver = self::singleton();
		if ( !$driver ) {
			throw new MWException( __METHOD__.
				': tidy is disabled, caller should have checked MWTidy::isEnabled()' );
		}
		if ( $driver->supportsValidate() ) {
			return $driver->validate( $text, $errorStr );
		} else {
			throw new MWException( __METHOD__ . ": error text return from HHVM tidy is not supported" );
		}
	}

	public static function isEnabled() {
		return self::singleton() !== false;
	}

	protected static function singleton() {
		global $wgUseTidy, $wgTidyInternal, $wgTidyConf, $wgDebugTidy, $wgTidyConfig,
			$wgTidyBin, $wgTidyOpts;

		if ( self::$instance === null ) {
			if ( $wgTidyConfig !== null ) {
				$config = $wgTidyConfig;
			} elseif ( $wgUseTidy ) {
				// b/c configuration
				$config = array(
					'tidyConfigFile' => $wgTidyConf,
					'debugComment' => $wgDebugTidy,
					'tidyBin' => $wgTidyBin,
					'tidyCommandLine' => $wgTidyOpts );
				if ( $wgTidyInternal ) {
					if ( wfIsHHVM() ) {
						$config['driver'] = 'RaggettInternalHHVM';
					} else {
						$config['driver'] = 'RaggettInternalPHP';
					}
				} else {
					$config['driver'] = 'RaggettExternal';
				}
			} else {
				return false;
			}
			switch ( $config['driver'] ) {
				case 'RaggettInternalHHVM':
					self::$instance = new MediaWiki\Tidy\RaggettInternalHHVM( $config );
					break;
				case 'RaggettInternalPHP':
					self::$instance = new MediaWiki\Tidy\RaggettInternalPHP( $config );
					break;
				case 'RaggettExternal':
					self::$instance = new MediaWiki\Tidy\RaggettExternal( $config );
					break;
				case 'Html5Depurate':
					self::$instance = new MediaWiki\Tidy\Html5Depurate( $config );
					break;
				default:
					throw new MWException( "Invalid tidy driver: \"{$config['driver']}\"" );
			}
		}
		return self::$instance;
	}

	/**
	 * Set the driver to be used. This is for testing.
	 * @param TidyDriverBase|false|null $instance
	 */
	public static function setInstance( $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Destroy the current singleton instance
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}
}
