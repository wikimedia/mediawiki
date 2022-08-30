<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\Parsoid\HTMLTransform;
use MediaWiki\Parser\Parsoid\HTMLTransformFactory;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;

/**
 * @coversDefaultClass \MediaWiki\Parser\Parsoid\HTMLTransformFactory
 */
class HTMLTransformFactoryTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers ::__construct
	 */
	public function testGetContentTransformFactory() {
		$factory = $this->getServiceContainer()->getHTMLTransformFactory();
		$this->assertInstanceOf( HTMLTransformFactory::class, $factory );
	}

	/**
	 * @covers ::getHTMLTransform
	 */
	public function testGetHTMLTransform() {
		$factory = $this->getServiceContainer()->getHTMLTransformFactory();
		$modifiedHTML = '<p>Hello World</p>';

		$transform = $factory->getHTMLTransform(
			$modifiedHTML,
			PageIdentityValue::localIdentity( 0, NS_MAIN, 'Test' )
		);

		$this->assertInstanceOf( HTMLTransform::class, $transform );

		$actualHTML = ContentUtils::toXML( DOMCompat::getBody( $transform->getModifiedDocument() ) );
		$this->assertStringContainsString( $modifiedHTML, $actualHTML );
	}

}
