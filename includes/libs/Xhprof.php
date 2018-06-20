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
 * Convenience class for working with XHProf
 * <https://github.com/phacility/xhprof>. XHProf can be installed as a PECL
 * package for use with PHP5 (Zend PHP) and is built-in to HHVM 3.3.0.
 *
 * This also supports using the Tideways profiler
 * <https://github.com/tideways/php-profiler-extension>, which additionally
 * has support for PHP7.
 *
 * @since 1.28
 */
class Xhprof {
	/**
	 * @var bool $enabled Whether XHProf is currently running.
	 */
	protected static $enabled;

	/**
	 * Start xhprof profiler
	 * @return bool
	 */
	public static function isEnabled() {
		return self::$enabled;
	}

	/**
	 * Start xhprof profiler
	 * @param int $flags
	 * @param array $options
	 * @throws Exception
	 */
	public static function enable( $flags = 0, $options = [] ) {
		if ( self::isEnabled() ) {
			throw new Exception( 'Profiling is already enabled.' );
		}
		self::$enabled = true;
		self::callAny(
			[
				'xhprof_enable',
				'tideways_enable',
				'tideways_xhprof_enable'
			],
			[ $flags, $options ]
		);
	}

	/**
	 * Stop xhprof profiler
	 *
	 * @return array|null xhprof data from the run, or null if xhprof was not running.
	 */
	public static function disable() {
		if ( self::isEnabled() ) {
			self::$enabled = false;
			return self::callAny( [
				'xhprof_disable',
				'tideways_disable',
				'tideways_xhprof_disable'
			] );
		}
	}

	/**
	 * Call the first available function from $functions.
	 * @param array $functions
	 * @param array $args
	 * @throws Exception
	 */
	protected static function callAny( array $functions, array $args = [] ) {
		foreach ( $functions as $func ) {
			if ( function_exists( $func ) ) {
				return $func( ...$args );
			}
		}

		throw new Exception( "Neither xhprof nor tideways are installed" );
	}
}
