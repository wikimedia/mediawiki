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
 * <https://github.com/phacility/xhprof>. XHProf can be installed via PECL.
 *
 * This also supports using the Tideways profiler
 * <https://github.com/tideways/php-xhprof-extension>.
 *
 * @internal For use by ProfilerXhprof
 * @since 1.28
 */
class Xhprof {

	/**
	 * Start profiler
	 *
	 * @param int $flags
	 * @param array $options
	 */
	public static function enable( $flags = 0, $options = [] ) {
		$args = [ $flags ];
		if ( $options ) {
			$args[] = $options;
		}

		self::callAny(
			[
				'xhprof_enable',
				'tideways_enable',
				'tideways_xhprof_enable'
			],
			$args
		);
	}

	/**
	 * Stop profiler
	 *
	 * @return array Xhprof data since last enable call,
	 *  or empty array if it was never enabled.
	 */
	public static function disable() {
		return self::callAny( [
			'xhprof_disable',
			'tideways_disable',
			'tideways_xhprof_disable'
		] );
	}

	/**
	 * Call the first available function from $functions.
	 * @param array $functions
	 * @param array $args
	 * @return mixed
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
