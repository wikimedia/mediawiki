<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parsoid\HtmlToContentTransform;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;

/**
 * @coversDefaultClass \MediaWiki\Parser\Parsoid\HtmlTransformFactory
 */
class HtmlTransformFactoryTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers ::__construct
	 */
	public function testGetContentTransformFactory() {
		$factory = $this->getServiceContainer()->getHtmlTransformFactory();
		$this->assertInstanceOf( HtmlTransformFactory::class, $factory );
	}

	/**
	 * @covers ::getHtmlToContentTransform
	 */
	public function testGetHtmlToContentTransform() {
		$factory = $this->getServiceContainer()->getHtmlTransformFactory();
		$modifiedHTML = '<p>Hello World</p>';

		$transform = $factory->getHtmlToContentTransform(
			$modifiedHTML,
			PageIdentityValue::localIdentity( 0, NS_MAIN, 'Test' )
		);

		$this->assertInstanceOf( HtmlToContentTransform::class, $transform );

		$actualHTML = ContentUtils::toXML( DOMCompat::getBody( $transform->getModifiedDocument() ) );
		$this->assertStringContainsString( $modifiedHTML, $actualHTML );
	}

}
