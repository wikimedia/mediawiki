<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace MediaWiki\Composer;

use Composer\Composer;
use RuntimeException;

/**
 * Checks the version of Composer that this is being run on, and aborts if not version 2 or later
 *
 * @internal
 * @since 1.42
 */
class VersionChecker {
	public static function onEvent() {
		$version = Composer::VERSION;
		if ( $version === '@package_version@' ) {
			// In Composer 1.9+, unreleased git branches have this value in Composer::VERSION,
			// and Composer::getVersion() was introduced to work around this.
			$version = Composer::getVersion();
		}
		if ( version_compare( $version, '2.0.0', '<' ) ) {
			throw new RuntimeException(
				"MediaWiki requires Composer version 2 or later; version 1"
				. " has been considered end of life since October 2020!"
			);
		}
	}
}
