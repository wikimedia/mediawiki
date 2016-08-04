<?php
/**
 * Media-handling base classes and generic functionality.
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
 * @ingroup Media
 */

/**
 * Replace all media handlers with a mock. We do not need to generate
 * actual thumbnails to do parser testing, we only care about receiving
 * a ThumbnailImage properly initialized.
 *
 * @since 1.28
 */
class MockMediaHandlerFactory extends MediaHandlerFactory {

	private static $overrides = [
		'image/svg+xml' => MockSvgHandler::class,
		'image/vnd.djvu' => MockDjVuHandler::class,
		'application/ogg' => MockOggHandler::class,
	];

	public function __construct() {
		// override parent
	}

	protected function getHandlerClass( $type ) {
		if ( isset( self::$overrides[$type] ) ) {
			return self::$overrides[$type];
		}

		return MockBitmapHandler::class;
	}

}
