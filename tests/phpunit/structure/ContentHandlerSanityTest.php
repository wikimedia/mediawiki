<?php

use Wikimedia\TestingAccessWrapper;

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
 */

class ContentHandlerSanityTest extends MediaWikiTestCase {

	public static function provideMakeEmptyContent() {
		$models = ContentHandler::getContentModels();
		$handlers = [];
		foreach ( $models as $model ) {
			$handlers[] = [ ContentHandler::getForModelID( $model ) ];
		}

		return $handlers;
	}

	/**
	 * @dataProvider provideMakeEmptyContent
	 * @param ContentHandler $handler
	 */
	public function testMakeEmptyContent( ContentHandler $handler ) {
		$handlerClass = get_class( $handler );

		try {
			$content = $handler->makeEmptyContent();
			$this->assertInstanceOf( Content::class, $content, $handlerClass );
		} catch ( MWException $ex ) {
			$this->assertFalse(
				$handler->supportsDirectEditing(),
				"$handlerClass::makeEmptyContent() throw an exception - that's only allowed if "
					. "$handlerClass::supportsDirectEditing() returns false."
			);

			$this->assertFalse(
				$handler->supportsDirectApiEditing(),
				"$handlerClass::makeEmptyContent() throw an exception - that's only allowed if "
				. "$handlerClass::supportsDirectApiEditing() returns false."
			);
		}
	}

}
