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

use MediaWiki\Content\Content;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\TextContentHandler;
use MediaWiki\Content\Transform\PreloadTransformParamsValue;
use MediaWiki\Content\Transform\PreSaveTransformParamsValue;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @coversNothing
 */
class ContentHandlerFunctionalTest extends MediaWikiIntegrationTestCase {

	public function testMakeEmptyContent() {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		foreach ( $contentHandlerFactory->getContentModels() as $model ) {
			$handler = $this->getServiceContainer()->getContentHandlerFactory()
				->getContentHandler( $model );

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

	/**
	 * Test that getParserOutput works on all content models
	 */
	public function testGetParserOutput() {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		foreach ( $contentHandlerFactory->getContentModels() as $model ) {
			$this->filterDeprecated( '/Use of AbstractContent::getParserOutput was deprecated/' );

			$handler = $this->getServiceContainer()->getContentHandlerFactory()
				->getContentHandler( $model );

			$title = $this->getExistingTestPage()->getTitle();
			$content = $handler->makeEmptyContent();

			$gpoParams = new ContentParseParams( $title );
			$this->assertInstanceOf(
				ParserOutput::class,
				$handler->getParserOutput( $content, $gpoParams )
			);
		}
	}

	/**
	 * Test that preSaveTransform works on all content models
	 */
	public function testPreSaveTransform() {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		foreach ( $contentHandlerFactory->getContentModels() as $model ) {
			$this->filterDeprecated( '/Use of AbstractContent::preSaveTransform was deprecated/' );

			$handler = $this->getServiceContainer()->getContentHandlerFactory()
				->getContentHandler( $model );

			$title = $this->getExistingTestPage()->getTitle();
			$user = $this->getTestUser()->getUser();
			$popts = ParserOptions::newFromAnon();
			$content = $handler->makeEmptyContent();

			$pstParams = new PreSaveTransformParamsValue( $title, $user, $popts );
			$this->assertInstanceOf(
				Content::class,
				$handler->preSaveTransform( $content, $pstParams )
			);
		}
	}

	/**
	 * Test that preloadTransform works on all content models
	 */
	public function testPreloadTransform() {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		foreach ( $contentHandlerFactory->getContentModels() as $model ) {
			$this->filterDeprecated( '/Use of AbstractContent::preloadTransform was deprecated/' );

			$handler = $this->getServiceContainer()->getContentHandlerFactory()
				->getContentHandler( $model );

			$title = $this->getExistingTestPage()->getTitle();
			$popts = ParserOptions::newFromAnon();
			$content = $handler->makeEmptyContent();

			$pltParams = new PreloadTransformParamsValue( $title, $popts, [] );
			$this->assertInstanceOf(
				Content::class,
				$handler->preloadTransform( $content, $pltParams )
			);
		}
	}

	/**
	 * Test that serialization and unserialization works on all content models
	 */
	public function testSerializationRoundTrips() {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		foreach ( $contentHandlerFactory->getContentModels() as $model ) {
			if ( preg_match( '/^wikibase-/', $model ) ) {
				// TODO: Make Wikibase support serialization of empty content, just so
				//       we can test it here.
				$this->markTestSkipped( 'Wikibase doesn\'t support serializing empty content' );
			}

			$handler = $this->getServiceContainer()->getContentHandlerFactory()
				->getContentHandler( $model );

			$content = $handler->makeEmptyContent();
			$data = $content->serialize();
			$content2 = $handler->unserializeContent( $data );
			$this->assertTrue( $content->equals( $content2 ) );
		}
	}
}
