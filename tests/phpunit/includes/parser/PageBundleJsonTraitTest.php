<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Parser\Parsoid\PageBundleJsonTrait;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * @covers \MediaWiki\Parser\Parsoid\PageBundleJsonTrait
 */
class PageBundleJsonTraitTest extends MediaWikiIntegrationTestCase {

	private const BUNDLE_DATA = [
		'html' => '<h1>woohoo</h1>',
		'parsoid' => [ 'metadata' => 'foo' ],
		'mw' => null,
		'version' => '1.0',
		'headers' => [ 'bar' => 'baz' ],
		'contentmodel' => 'default'
	];

	public function testNewPageBundleFromJson() {
		$trait = new class {
			use PageBundleJsonTrait {
				newPageBundleFromJson as public;
			}
		};
		$bundle = $trait->newPageBundleFromJson( self::BUNDLE_DATA );
		$this->assertInstanceOf( PageBundle::class, $bundle );
		$this->assertEquals( '<h1>woohoo</h1>', $bundle->html );
		$this->assertEquals( 'default', $bundle->contentmodel );
		$this->assertNull( $bundle->mw );
	}

	public function testJsonSerializePageBundle() {
		$trait = new class {
			use PageBundleJsonTrait {
				jsonSerializePageBundle as public;
			}
		};
		$bundle = new PageBundle( ...array_values( self::BUNDLE_DATA ) );
		$json = $trait->jsonSerializePageBundle( $bundle );
		$this->assertEquals( PageBundle::class, $json['_type_'] );
		$this->assertEquals( '<h1>woohoo</h1>', $json['html'] );
		$this->assertNull( $json['mw'] );
	}
}
