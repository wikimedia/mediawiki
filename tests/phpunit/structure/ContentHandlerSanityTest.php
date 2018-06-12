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

	public static function provideHandlers() {
		$models = ContentHandler::getContentModels();
		$handlers = [];
		foreach ( $models as $model ) {
			$handlers[] = [ ContentHandler::getForModelID( $model ) ];
		}

		return $handlers;
	}

	/**
	 * @coversNothing
	 * @dataProvider provideHandlers
	 * @param ContentHandler $handler
	 */
	public function testMakeEmptyContent( ContentHandler $handler ) {
		$content = $handler->makeEmptyContent();
		$this->assertInstanceOf( Content::class, $content );
		if ( $handler instanceof TextContentHandler ) {
			// TextContentHandler::getContentClass() is protected, so bypass
			// that restriction
			$testingWrapper = TestingAccessWrapper::newFromObject( $handler );
			$this->assertInstanceOf( $testingWrapper->getContentClass(), $content );
		}

		$handlerClass = get_class( $handler );
		$contentClass = get_class( $content );

		if ( $handler->supportsDirectEditing() ) {
			$this->assertTrue(
				$content->isValid(),
				"$handlerClass::makeEmptyContent() did not return a valid content ($contentClass::isValid())"
			);
		}
	}

}
