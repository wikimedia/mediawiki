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

namespace MediaWiki\Logger;

/**
 * Backwards compatible PSR-3 logger instance factory.
 *
 * PSR-3 debug logging was introduced to MediaWiki in 1.25. This class
 * provides a backward compatible PSR-3 logging layer to make backporting
 * critical updates from 1.25+ easier. It also serves to allow extensions that
 * maintain backwards compatibility with the 1.23 LTS releases to migrate to
 * the new logging system.
 *
 * @since 1.23.10
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 */
class LoggerFactory {

	/**
	 * Get a named logger instance.
	 *
	 * @param string $channel Logger channel (name)
	 * @return \Psr\Log\LoggerInterface
	 */
	public static function getInstance( $channel ) {
		return new BackcompatLogger( $channel );
	}

	/**
	 * Construction of utility class is not allowed.
	 */
	private function __construct() {
		// no-op
	}
}
